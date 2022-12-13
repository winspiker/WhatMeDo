<?php

declare(strict_types=1);

namespace App\Types;

class Password
{
    private string $password;

    public function __construct(string $password)
    {
       $this->password = $this->hashPassword($password);
    }

    public function equals(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    private function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function getValue(): string
    {
        return $this->password;
    }

}