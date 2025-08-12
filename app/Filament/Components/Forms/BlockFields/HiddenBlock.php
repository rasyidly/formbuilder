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
