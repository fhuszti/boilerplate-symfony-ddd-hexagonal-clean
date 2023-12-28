<?php

namespace App\Domain\User\Entity;

use App\Domain\Security\Permission\AdminUserPermissionsEnum;
use App\Domain\Security\Permission\UserPermissionsEnum;
use App\Domain\Security\RoleEnum;
use App\Domain\User\Entity\ValueObject\EmailAddress;
use App\Domain\User\Entity\ValueObject\Password;
use App\Domain\User\Entity\ValueObject\Username;

class User
{
    private int $id;
    private Username $username;
    private EmailAddress $email;
    private Password $password;
    private RoleEnum $role;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function setUsername(Username $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function setEmail(EmailAddress $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    public function getRole(): RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): void
    {
        $this->role = $role;
    }

    /**
     * @return array<int, UserPermissionsEnum|AdminUserPermissionsEnum>
     */
    public function getPermissions(): array
    {
        return $this->role->getPermissions();
    }
}
