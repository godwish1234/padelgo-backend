<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'user';
    case COURT_ADMIN = 'court_admin';
    case SUPER_ADMIN = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::COURT_ADMIN => 'Court Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::COURT_ADMIN || $this === self::SUPER_ADMIN;
    }
}
