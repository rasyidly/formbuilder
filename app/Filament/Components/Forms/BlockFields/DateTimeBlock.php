<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class DateTimeBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('datetime')
            ->label('Date & Time')
            ->icon('heroicon-o-calendar-days')
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden()
                    ->helperText('Unique identifier for this field'),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required(),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2),
                Forms\Components\DateTimePicker::make('min_date')
                    ->label('Minimum Datetime'),
                Forms\Components\DateTimePicker::make('max_date')
                    ->label('Maximum Datetime'),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
            ]);
    }
}
