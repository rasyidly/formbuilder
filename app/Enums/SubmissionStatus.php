<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubmissionStatus: string implements HasLabel
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Archived = 'Archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Archived => 'Archived',
        };
    }
}
