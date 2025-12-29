<?php

namespace App\Enums;

enum MatchType: string
{
    case FRIENDLY = 'friendly';
    case COMPETITIVE = 'competitive';

    public function label(): string
    {
        return match ($this) {
            self::FRIENDLY => 'Friendly',
            self::COMPETITIVE => 'Competitive',
        };
    }
}
