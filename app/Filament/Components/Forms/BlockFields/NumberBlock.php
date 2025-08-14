<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class NumberBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('number')
            ->label(fn(?array $state) => $state['label'] ?? 'Number')
            ->icon('heroicon-o-hashtag')
            ->columns(3)
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden(),
                Forms\Components\TextInput::make('label')
                    ->live()
                    ->label('Label')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('placeholder')
                    ->label('Placeholder')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->columnSpanFull()
                    ->rows(2),
                Forms\Components\TextInput::make('min')
                    ->label('Minimum Value')
                    ->numeric(),
                Forms\Components\TextInput::make('max')
                    ->label('Maximum Value')
                    ->numeric(),
                Forms\Components\TextInput::make('step')
                    ->label('Step')
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false)
                    ->columnSpanFull(),

                Forms\Components\Group::make([
                    Forms\Components\ToggleButtons::make('col_span')
                        ->label('Column Width')
                        ->inline()
                        ->grouped()
                        ->options([
                            'full' => 'Full',
                            3 => 'Half',
                        ]),
                    Forms\Components\ToggleButtons::make('col_start')
                        ->label('Column Start')
                        ->inline()
                        ->grouped()
                        ->options([
                            1 => 'Left',
                            4 => 'Right',
                        ]),
                ])->columns(['xl' => 2])
            ]);
    }
}
