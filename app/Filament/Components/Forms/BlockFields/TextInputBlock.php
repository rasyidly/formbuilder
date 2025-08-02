<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class TextInputBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('text')
            ->label('Text Input')
            ->icon('heroicon-o-pencil')
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\TextInput::make('name')
                    ->label('Field Name')
                    ->required()
                    ->hidden()
                    ->helperText('Unique identifier for this field'),
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required(),
                Forms\Components\TextInput::make('placeholder')
                    ->label('Placeholder'),
                Forms\Components\Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
                Forms\Components\TagsInput::make('validation_rules')
                    ->label('Validation Rules')
                    ->helperText('e.g., min:3, max:255, alpha_num'),
            ]);
    }
}
