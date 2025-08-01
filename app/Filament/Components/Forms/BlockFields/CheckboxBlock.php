<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class CheckboxBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('checkbox')
            ->label('Checkbox')
            ->icon('heroicon-m-check')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->hidden()
                    ->required(),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required(),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
            ]);
    }
}
