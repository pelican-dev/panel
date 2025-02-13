<?php

namespace App\Tests\Unit\Helpers;

use App\Tests\TestCase;
use App\Traits\EnvironmentWriterTrait;
use PHPUnit\Framework\Attributes\DataProvider;

class EnvironmentWriterTraitTest extends TestCase
{
    #[DataProvider('variableDataProvider')]
    public function test_variable_is_escaped_properly($input, $expected): void
    {
        $output = (new FooClass())->escapeEnvironmentValue($input);

        $this->assertSame($expected, $output);
    }

    public static function variableDataProvider(): array
    {
        return [
            ['foo', 'foo'],
            ['abc123', 'abc123'],
            ['val"ue', '"val\"ue"'],
            ['val\'ue', '"val\'ue"'],
            ['my test value', '"my test value"'],
            ['mysql_p@assword', '"mysql_p@assword"'],
            ['mysql_p#assword', '"mysql_p#assword"'],
            ['mysql p@$$word', '"mysql p@$$word"'],
            ['mysql p%word', '"mysql p%word"'],
            ['mysql p#word', '"mysql p#word"'],
            ['abc_@#test', '"abc_@#test"'],
            ['test 123 $$$', '"test 123 $$$"'],
            ['#password%', '"#password%"'],
            ['$pass ', '"$pass "'],
        ];
    }
}

class FooClass
{
    use EnvironmentWriterTrait;
}
