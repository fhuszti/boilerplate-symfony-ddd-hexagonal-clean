<?php

namespace App\Domain\Security;

use App\Domain\Security\Permission\AdminUserPermissionsEnum;
use App\Domain\Security\Permission\FreeUserPermissionsEnum;
use App\Domain\Security\Permission\SubscribedUserPermissionsEnum;

enum RoleEnum: string
{
    case Free = 'free';
    case Subscribed = 'subscribed';
    case Admin = 'admin';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<int, FreeUserPermissionsEnum|SubscribedUserPermissionsEnum|AdminUserPermissionsEnum>
     */
    public function getPermissions(): array
    {
        return match ($this) {
            RoleEnum::Free => $this->getFreeUserPermissions(),
            RoleEnum::Subscribed => $this->getSubscribedUserPermissions(),
            RoleEnum::Admin => $this->getAdminUserPermissions()
        };
    }

    /**
     * @return FreeUserPermissionsEnum[]
     */
    private function getFreeUserPermissions(): array
    {
        return FreeUserPermissionsEnum::cases();
    }

    /**
     * @return array<int, FreeUserPermissionsEnum|SubscribedUserPermissionsEnum>
     */
    private function getSubscribedUserPermissions(): array
    {
        return [
            ...FreeUserPermissionsEnum::cases(),
            ...SubscribedUserPermissionsEnum::cases(),
        ];
    }

    /**
     * @return array<int, FreeUserPermissionsEnum|SubscribedUserPermissionsEnum|AdminUserPermissionsEnum>
     */
    private function getAdminUserPermissions(): array
    {
        return [
            ...FreeUserPermissionsEnum::cases(),
            ...SubscribedUserPermissionsEnum::cases(),
            ...AdminUserPermissionsEnum::cases(),
        ];
    }
}
