<?php

namespace App\Filament\Components\Forms\BlockFields;

use Filament\Forms;

class TextareaBlock
{
    public static function make(): Forms\Components\Builder\Block
    {
        return Forms\Components\Builder\Block::make('textarea')
            ->label(fn(?array $state) => $state['label'] ?? 'Textarea')
            ->icon('heroicon-o-document-text')
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
                Forms\Components\TextInput::make('rows')
                    ->label('Number of Rows')
                    ->numeric()
                    ->default(3),
                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),
                Forms\Components\TagsInput::make('validation_rules')
                    ->label('Validation Rules')
                    ->helperText('e.g., min:3, max:255, alpha_num')
                    ->hintAction(
                        Forms\Components\Actions\Action::make('See Validation Rules')
                            ->url('https://laravel.com/docs/12.x/validation#available-validation-rules')
                            ->openUrlInNewTab()
                            ->color('gray')
                    ),

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
