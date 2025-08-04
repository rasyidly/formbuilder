<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class FileBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('file')
            ->label(fn(?array $state) => $state['label'] ?? 'File Upload')
            ->icon('heroicon-o-document-arrow-up')
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

                Forms\Components\Group::make([
                    Forms\Components\ToggleButtons::make('col_span')
                        ->label('Column Width')
                        ->inline()
                        ->grouped()
                        ->options([
                            1 => '1/6',
                            2 => '2/6',
                            3 => '3/6',
                            4 => '4/6',
                            5 => '5/6',
                            6 => 'Full',
                        ])
                        ->helperText('How many columns this field should span in the layout, set "Full" or leave empty for full width'),
                    Forms\Components\ToggleButtons::make('col_start')
                        ->label('Column Start')
                        ->inline()
                        ->grouped()
                        ->options([
                            1 => '1st',
                            2 => '2nd',
                            3 => '3rd',
                            4 => '4th',
                            5 => '5th',
                            6 => '6th',
                        ])
                        ->helperText('Select the column where this field should begin in the layout. Choose "1st" to start at the first column, or leave blank to use the default starting position.'),
                ])->columns(['xl' => 2])
            ]);
    }
}
