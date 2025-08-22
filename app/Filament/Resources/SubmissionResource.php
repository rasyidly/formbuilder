<?php

namespace App\Filament\Resources;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\SubmissionResource\Pages;
use App\Models;
use App\Models\Submission;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationGroup = 'Forms';

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['lg' => 3])
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('form_id')
                        ->label('Form')
                        ->relationship('form', 'name')
                        ->searchable()
                        ->required()
                        ->disabled(fn(string $operation) => $operation === 'edit')
                        ->preload()
                        ->live()
                        ->columnSpan(2),
                    Forms\Components\Placeholder::make('form_status')
                        ->label('Form Status')
                        ->content(function ($get) {
                            if ($form = Models\Form::withTrashed()->find($get('form_id'))) {
                                return match (true) {
                                    $form->trashed() => 'Deleted',
                                    (bool) $form->archived_at => 'Archived',
                                    (bool) $form->published_at => 'Published',
                                    default => 'Draft',
                                };
                            }

                            return '-';
                        }),
                ])->columns(3),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Forms')
                        ->label('Form Fields')
                        ->schema(function (callable $get) {
                            if ($form = Models\Form::with('fields')->find($get('form_id'))) {
                                return $form->fields->map(function (Models\FormField $field) {
                                    return $field->type->getField($field)
                                        ->statePath('values.' . $field->id)
                                        ->label($field->label)
                                        ->required($field->is_required)
                                        ->helperText($field->help_text)
                                        ->key($field->id);
                                })->toArray();
                            }

                            return [];
                        }),
                ])->columnSpan(['lg' => 2])->visible(fn($get) => $get('form_id') !== null),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Submission Details')
                        ->schema([
                            Forms\Components\TextInput::make('submitter_ip')
                                ->label('Submitter IP')
                                ->disabled()
                                ->visible(fn(string $operation) => $operation === 'edit'),
                            Forms\Components\TextInput::make('user_agent')
                                ->label('User Agent')
                                ->disabled()
                                ->visible(fn(string $operation) => $operation === 'edit'),
                            Forms\Components\DateTimePicker::make('created_at')
                                ->label('Created At')
                                ->disabled()
                                ->visible(fn(string $operation) => $operation === 'edit'),
                        ]),
                    Forms\Components\Section::make('Notes')
                        ->schema([
                            Forms\Components\Textarea::make('notes')
                                ->hiddenLabel()
                                ->rows(3)
                                ->placeholder('Enter any additional notes here...')
                        ])
                ])->visible(fn($get) => $get('form_id') !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['form.name'])
            ->columns([
                Tables\Columns\TextColumn::make('form.name')
                    ->label('Form')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('submitter_ip')
                    ->label('Submitter IP'),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(40),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(100),
                Tables\Columns\TextColumn::make('values_count')
                    ->label('Fields Filled')
                    ->counts('values'),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form_id')
                    ->label('Form')
                    ->relationship('form', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}
