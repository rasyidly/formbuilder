<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class CheckboxListBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('checkbox_list')
            ->label('Checkbox List')
            ->icon('heroicon-o-check-circle')
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
                Forms\Components\Repeater::make('options')
                    ->label('Options')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->key(null)
                            ->label('Label')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!empty($state)) {
                                    $set('value', $state);
                                }
                            }),
                        Forms\Components\TextInput::make('value')
                            ->key(null)
                            ->label('Value')
                    ])
                    ->required()
                    ->minItems(1),
                Forms\Components\Toggle::make('inline')
                    ->label('Display Inline')
                    ->default(false),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
            ]);
    }
}
