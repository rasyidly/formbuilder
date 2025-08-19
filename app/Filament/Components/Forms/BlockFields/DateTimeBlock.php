<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class DateTimeBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('datetime')
            ->label(fn(?array $state) => $state['label'] ?? 'Date & Time Picker')
            ->icon('heroicon-o-calendar-days')
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden()
                    ->helperText('Unique identifier for this field'),
                Forms\Components\TextInput::make('label')
                    ->live()
                    ->label('Label')
                    ->required(),
                // Forms\Components\Textarea::make('help_text')
                //     ->label('Help Text')
                //     ->rows(2),
                Forms\Components\DateTimePicker::make('min_date')
                    ->label('Minimum Datetime'),
                Forms\Components\DateTimePicker::make('max_date')
                    ->label('Maximum Datetime'),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),

                Forms\Components\Group::make([
                    Forms\Components\ToggleButtons::make('col_span')
                        ->label('Column Width')
                        ->inline()
                        ->grouped()
                        ->options([
                            'full' => 'Full',
                            6 => 'Half',
                        ]),
                ])->columns(['xl' => 2])
            ]);
    }
}
