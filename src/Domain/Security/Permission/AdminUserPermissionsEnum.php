<?php

namespace App\Domain\Security\Permission;

enum AdminUserPermissionsEnum: string
{
    case MANAGE_USERS = 'manageUsers';
}
