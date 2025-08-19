<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class TimeBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('time')
            ->label(fn(?array $state) => $state['label'] ?? 'Time Picker')
            ->icon('heroicon-o-clock')
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden(),
                Forms\Components\TextInput::make('label')
                    ->live()
                    ->label('Label')
                    ->required(),
                // Forms\Components\Textarea::make('help_text')
                //     ->label('Help Text')
                //     ->rows(2),
                Forms\Components\Toggle::make('seconds')
                    ->label('Include Seconds')
                    ->default(false),
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
