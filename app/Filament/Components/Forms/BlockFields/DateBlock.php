<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class DateBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('date')
            ->label('Date Picker')
            ->icon('heroicon-m-calendar-days')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden(),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required(),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2),
                Forms\Components\DatePicker::make('min_date')
                    ->label('Minimum Date'),
                Forms\Components\DatePicker::make('max_date')
                    ->label('Maximum Date'),
                Forms\Components\Toggle::make('display_format')
                    ->label('Custom Display Format'),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
            ]);
    }
}
