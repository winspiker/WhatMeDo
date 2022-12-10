<?php

declare(strict_types=1);

namespace App\Service;
final class HashPasswordService
{
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}