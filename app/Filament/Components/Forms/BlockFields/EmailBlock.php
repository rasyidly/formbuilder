<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class EmailBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('email')
            ->label(fn(?array $state) => $state['label'] ?? 'Email')
            ->icon('heroicon-o-envelope')
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
                Forms\Components\TextInput::make('placeholder')
                    ->label('Placeholder'),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2),
                Forms\Components\Toggle::make('receive_feedback')
                    ->label('Receive form submission feedback')
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
