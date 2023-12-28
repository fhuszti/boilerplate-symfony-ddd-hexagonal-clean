<?php

namespace App\Domain\Security;

use App\Domain\Security\Permission\AdminUserPermissionsEnum;
use App\Domain\Security\Permission\UserPermissionsEnum;

enum RoleEnum: string
{
    case User = 'user';
    case Admin = 'admin';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<int, UserPermissionsEnum|AdminUserPermissionsEnum>
     */
    public function getPermissions(): array
    {
        return match ($this) {
            RoleEnum::User => $this->getUserPermissions(),
            RoleEnum::Admin => $this->getAdminUserPermissions()
        };
    }

    /**
     * @return UserPermissionsEnum[]
     */
    private function getUserPermissions(): array
    {
        return UserPermissionsEnum::cases();
    }

    /**
     * @return array<int, UserPermissionsEnum|AdminUserPermissionsEnum>
     */
    private function getAdminUserPermissions(): array
    {
        return [
            ...UserPermissionsEnum::cases(),
            ...AdminUserPermissionsEnum::cases(),
        ];
    }
}
