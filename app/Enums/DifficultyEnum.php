<?php

namespace App\Enums;

enum DifficultyEnum: string
{
    case JUNIOR = 'junior';
    case MID = 'mid';
    case SENIOR = 'senior';

    public function label(): string
    {
        return match ($this) {
            self::JUNIOR => 'Junior',
            self::MID => 'Mid',
            self::SENIOR => 'Senior',
        };
    }
}