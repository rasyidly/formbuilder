<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class CheckboxBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('checkbox')
            ->label('Checkboxes')
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
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->key(null)
                            ->label('Label')
                            ->required()
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
