<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class TextareaBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('textarea')
            ->label('Textarea')
            ->icon('heroicon-o-document-text')
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
                Forms\Components\TextInput::make('rows')
                    ->label('Number of Rows')
                    ->numeric()
                    ->default(3),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
                Forms\Components\TagsInput::make('validation_rules')
                    ->label('Validation Rules'),
            ]);
    }
}
