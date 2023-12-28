<?php

namespace App\Tests\Unit\Domain\Security;

use App\Domain\Security\Permission\AdminUserPermissionsEnum;
use App\Domain\Security\Permission\UserPermissionsEnum;
use App\Domain\Security\RoleEnum;
use PHPUnit\Framework\TestCase;

class RoleEnumTest extends TestCase
{
    public function testValuesMethod(): void
    {
        $expectedValues = ['user', 'admin'];
        $this->assertEquals($expectedValues, RoleEnum::values());
    }

    public function testGetPermissionsForUser(): void
    {
        $permissions = RoleEnum::User->getPermissions();
        $this->assertContainsOnlyInstancesOf(UserPermissionsEnum::class, $permissions);
    }

    public function testGetPermissionsForAdminUser(): void
    {
        $permissions = RoleEnum::Admin->getPermissions();

        foreach ($permissions as $permission) {
            $this->assertTrue(
                $permission instanceof UserPermissionsEnum
                || $permission instanceof AdminUserPermissionsEnum,
                'Permission is not an instance of the expected enum classes'
            );
        }
    }
}
