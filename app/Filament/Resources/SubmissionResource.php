<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Models;
use App\Models\Submission;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Select;
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
                        ->disabled(fn($context) => $context === 'edit')
                        ->preload()
                        ->live()
                        ->columnSpan(2),
                    Forms\Components\Placeholder::make('form_status')
                        ->label('Form Status')
                        ->content(function ($get) {
                            if ($form = Models\Form::withTrashed()->find($get('form_id')))
                                return match (true) {
                                    $form->trashed() => 'Deleted',
                                    (bool) $form->archived_at => 'Archived',
                                    (bool) $form->published_at => 'Published',
                                    default => 'Draft',
                                };
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
                            Select::make('submitter_id')
                                ->label('Submitter')
                                ->helperText('Select a user if the submission is associated with a registered user.')
                                ->relationship('submitter', 'name')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $user = User::find($state);
                                    $set('submitter_name', $user?->name);
                                    $set('submitter_email', $user?->email);
                                }),
                            Forms\Components\TextInput::make('submitter_name')
                                ->label('Submitter Name'),
                            Forms\Components\TextInput::make('submitter_email')
                                ->label('Submitter Email'),
                            Forms\Components\TextInput::make('submitter_ip')
                                ->label('Submitter IP')
                                ->disabled()
                                ->visible(fn($context) => $context === 'edit'),
                            Forms\Components\TextInput::make('user_agent')
                                ->label('User Agent')
                                ->disabled()
                                ->visible(fn($context) => $context === 'edit'),
                            Forms\Components\DateTimePicker::make('created_at')
                                ->label('Created At')
                                ->disabled()
                                ->visible(fn($context) => $context === 'edit'),
                        ]),
                ])->visible(fn($get) => $get('form_id') !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('form.name')
                    ->label('Form')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('values_count')
                    ->label('Fields Filled')
                    ->counts('values'),
                Tables\Columns\TextColumn::make('values_preview')
                    ->label('Preview')
                    ->getStateUsing(function ($record) {
                        return $record->values->map(fn($v) => $v->field_label . ': ' . $v->getDisplayValue())->implode(', ');
                    })
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form_id')
                    ->label('Form')
                    ->relationship('form', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
