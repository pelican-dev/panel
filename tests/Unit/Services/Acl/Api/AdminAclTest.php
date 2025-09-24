<?php

namespace App\Tests\Unit\Services\Acl\Api;

use App\Models\ApiKey;
use App\Models\Server;
use App\Services\Acl\Api\AdminAcl;
use App\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AdminAclTest extends TestCase
{
    /**
     * Test that permissions return the expects values.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_permissions(int $permission, int $check, bool $outcome): void
    {
        $this->assertSame($outcome, AdminAcl::can($permission, $check));
    }

    /**
     * Test that checking against a model works as expected.
     */
    public function test_check(): void
    {
        $model = ApiKey::factory()->make(['permissions' => [
            Server::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
        ]]);

        $this->assertTrue(AdminAcl::check($model, Server::RESOURCE_NAME, AdminAcl::WRITE));
    }

    /**
     * Provide valid and invalid permissions combos for testing.
     */
    public static function permissionsDataProvider(): array
    {
        return [
            [AdminAcl::READ, AdminAcl::READ, true],
            [AdminAcl::READ | AdminAcl::WRITE, AdminAcl::READ, true],
            [AdminAcl::READ | AdminAcl::WRITE, AdminAcl::WRITE, true],
            [AdminAcl::WRITE, AdminAcl::WRITE, true],
            [AdminAcl::READ, AdminAcl::WRITE, false],
            [AdminAcl::NONE, AdminAcl::READ, false],
            [AdminAcl::NONE, AdminAcl::WRITE, false],
        ];
    }
}
