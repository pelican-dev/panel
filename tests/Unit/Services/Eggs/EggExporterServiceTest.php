<?php

namespace App\Tests\Unit\Services\Eggs;

use App\Services\Eggs\Sharing\EggExporterService;
use PHPUnit\Framework\TestCase;

class EggExporterServiceTest extends TestCase
{
    private EggExporterService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new EggExporterService();
    }

    public function test_yaml_export_preserves_literal_backslash_n_in_scripts(): void
    {
        $script = <<<'BASH'
if [[ "${STEAM_USER}" == "" ]] || [[ "${STEAM_PASS}" == "" ]]; then
    echo -e "steam user is not set.\n"
    echo -e "Using anonymous user.\n"
    STEAM_USER=anonymous
    STEAM_PASS=""
    STEAM_AUTH=""
else
    echo -e "user set to ${STEAM_USER}"
fi
BASH;

        $result = $this->callYamlExport($script);

        $this->assertStringContainsString('echo -e "steam user is not set.\n"', $result);
        $this->assertStringContainsString('echo -e "Using anonymous user.\n"', $result);
    }

    public function test_yaml_export_preserves_literal_backslash_r_backslash_n(): void
    {
        $script = 'echo -e "line ending\\r\\n"';

        $result = $this->callYamlExport($script);

        $this->assertSame($script, $result);
    }

    public function test_yaml_export_normalizes_real_crlf_to_lf(): void
    {
        $script = "line one\r\nline two\r\nline three";

        $result = $this->callYamlExport($script);

        $this->assertSame("line one\nline two\nline three", $result);
    }

    /**
     * Call the protected yamlExport method via reflection.
     */
    private function callYamlExport(mixed $data): mixed
    {
        $reflection = new \ReflectionMethod($this->service, 'yamlExport');

        return $reflection->invoke($this->service, $data);
    }
}
