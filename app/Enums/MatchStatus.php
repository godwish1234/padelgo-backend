<?php

namespace App\Enums;

enum MatchStatus: string
{
    case OPEN = 'open';
    case FULL = 'full';
    case ONGOING = 'ongoing';
    case FINISHED = 'finished';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::FULL => 'Full',
            self::ONGOING => 'Ongoing',
            self::FINISHED => 'Finished',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function isActive(): bool
    {
        return $this === self::OPEN || $this === self::FULL || $this === self::ONGOING;
    }
}
