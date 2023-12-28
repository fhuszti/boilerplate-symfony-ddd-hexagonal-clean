<?php

namespace App\Domain\Security\Permission;

enum SubscribedUserPermissionsEnum: string
{
    case ACCESS_ALL_LEVELS = 'accessAllLevels';
}
