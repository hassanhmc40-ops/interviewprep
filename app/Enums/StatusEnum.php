<?php

namespace App\Enums;

enum StatusEnum: string
{
    case TO_REVIEW = 'to_review';
    case IN_PROGRESS = 'in_progress';
    case MASTERED = 'mastered';

    public function label(): string
    {
        return match ($this) {
            self::TO_REVIEW => 'To Review',
            self::IN_PROGRESS => 'In Progress',
            self::MASTERED => 'Mastered',
        };
    }

    public function next(): self
    {
        return match ($this) {
            self::TO_REVIEW => self::IN_PROGRESS,
            self::IN_PROGRESS => self::MASTERED,
            self::MASTERED => self::TO_REVIEW,
        };
    }
}