<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class SelectBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('select')
            ->label('Select Dropdown')
            ->icon('heroicon-m-chevron-down')
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
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Label')
                            ->required(),
                    ])
                    ->required()
                    ->minItems(1),
                Forms\Components\Toggle::make('multiple')
                    ->label('Allow Multiple Selection')
                    ->default(false),
                Forms\Components\Toggle::make('searchable')
                    ->label('Searchable')
                    ->default(false),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
            ]);
    }
}
