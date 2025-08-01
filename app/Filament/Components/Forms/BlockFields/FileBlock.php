<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class FileBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('file')
            ->label('File Upload')
            ->icon('heroicon-m-document-arrow-up')
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
                Forms\Components\Toggle::make('multiple')
                    ->label('Allow Multiple Files')
                    ->default(false),
                Forms\Components\TagsInput::make('accepted_file_types')
                    ->label('Accepted File Types')
                    ->helperText('e.g., pdf, doc, docx, jpg, png'),
                Forms\Components\TextInput::make('max_file_size')
                    ->label('Max File Size (MB)')
                    ->numeric()
                    ->default(10),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
            ]);
    }
}
