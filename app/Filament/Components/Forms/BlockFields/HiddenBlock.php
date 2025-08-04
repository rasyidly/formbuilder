<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class HiddenBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('hidden')
            ->label(fn(?array $state) => $state['label'] ?? 'Hidden Field')
            ->icon('heroicon-o-eye-slash')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden(),
                Forms\Components\TextInput::make('value')
                    ->label('Value')
                    ->required(),

                Forms\Components\Group::make([
                    Forms\Components\ToggleButtons::make('col_span')
                        ->label('Column Width')
                        ->inline()
                        ->grouped()
                        ->options([
                            1 => '1/6',
                            2 => '2/6',
                            3 => '3/6',
                            4 => '4/6',
                            5 => '5/6',
                            6 => 'Full',
                        ])
                        ->helperText('How many columns this field should span in the layout, set "Full" or leave empty for full width'),
                    Forms\Components\ToggleButtons::make('col_start')
                        ->label('Column Start')
                        ->inline()
                        ->grouped()
                        ->options([
                            1 => '1st',
                            2 => '2nd',
                            3 => '3rd',
                            4 => '4th',
                            5 => '5th',
                            6 => '6th',
                        ])
                        ->helperText('Select the column where this field should begin in the layout. Choose "1st" to start at the first column, or leave blank to use the default starting position.'),
                ])->columns(['xl' => 2])
            ]);
    }
}
