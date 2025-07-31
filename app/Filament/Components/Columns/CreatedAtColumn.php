<?php

namespace App\Filament\Components\Columns;

use Filament\Tables\Columns\TextColumn;

class CreatedAtColumn extends TextColumn
{
    public static function make(string $name = 'created_at'): static
    {
        return parent::make($name)
            ->sortable()
            ->since()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
