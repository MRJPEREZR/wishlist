<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'Regular User'
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}

?>