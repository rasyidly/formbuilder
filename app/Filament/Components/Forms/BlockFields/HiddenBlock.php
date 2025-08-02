<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class HiddenBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('hidden')
            ->label('Hidden Field')
            ->icon('heroicon-o-eye-slash')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden(),
                Forms\Components\TextInput::make('value')
                    ->label('Value')
                    ->required(),
            ]);
    }
}
