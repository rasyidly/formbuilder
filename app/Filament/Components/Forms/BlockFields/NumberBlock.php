<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class NumberBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('number')
            ->label('Number')
            ->icon('heroicon-o-hashtag')
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden(),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required(),
                Forms\Components\TextInput::make('placeholder')
                    ->label('Placeholder'),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
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
                    ->default(false),
            ]);
    }
}
