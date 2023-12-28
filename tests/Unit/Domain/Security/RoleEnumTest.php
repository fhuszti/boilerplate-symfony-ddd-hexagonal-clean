<?php

namespace App\Tests\Unit\Domain\Security;

use App\Domain\Security\Permission\AdminUserPermissionsEnum;
use App\Domain\Security\Permission\FreeUserPermissionsEnum;
use App\Domain\Security\Permission\SubscribedUserPermissionsEnum;
use App\Domain\Security\RoleEnum;
use PHPUnit\Framework\TestCase;

class RoleEnumTest extends TestCase
{
    public function testValuesMethod(): void
    {
        $expectedValues = ['free', 'subscribed', 'admin'];
        $this->assertEquals($expectedValues, RoleEnum::values());
    }

    public function testGetPermissionsForFreeUser(): void
    {
        $permissions = RoleEnum::Free->getPermissions();
        $this->assertContainsOnlyInstancesOf(FreeUserPermissionsEnum::class, $permissions);
    }

    public function testGetPermissionsForSubscribedUser(): void
    {
        $permissions = RoleEnum::Subscribed->getPermissions();

        foreach ($permissions as $permission) {
            $this->assertTrue(
                $permission instanceof FreeUserPermissionsEnum || $permission instanceof SubscribedUserPermissionsEnum,
                'Permission is not an instance of FreeUserPermissionsEnum or SubscribedUserPermissionsEnum'
            );
        }
    }

    public function testGetPermissionsForAdminUser(): void
    {
        $permissions = RoleEnum::Admin->getPermissions();

        foreach ($permissions as $permission) {
            $this->assertTrue(
                $permission instanceof FreeUserPermissionsEnum
                || $permission instanceof SubscribedUserPermissionsEnum
                || $permission instanceof AdminUserPermissionsEnum,
                'Permission is not an instance of the expected enum classes'
            );
        }
    }
}
