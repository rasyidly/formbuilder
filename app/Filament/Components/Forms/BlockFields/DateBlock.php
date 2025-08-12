<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class DateBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('date')
            ->label(fn(?array $state) => $state['label'] ?? 'Date Picker')
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
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2),
                Forms\Components\DatePicker::make('min_date')
                    ->label('Minimum Date'),
                Forms\Components\DatePicker::make('max_date')
                    ->label('Maximum Date'),
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
                            3 => 'Half',
                        ])
                        ->helperText('How many columns this field should span in the layout, set "Full" or leave empty for full width'),
                    Forms\Components\ToggleButtons::make('col_start')
                        ->label('Column Start')
                        ->inline()
                        ->grouped()
                        ->options([
                            1 => 'Left',
                            4 => 'Right',
                        ])
                        ->helperText('Select the column where this field should begin in the layout. Choose "Left" to start at the first column, or leave blank to use the default starting position.'),
                ])->columns(['xl' => 2])
            ]);
    }
}
