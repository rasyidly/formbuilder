<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Models\FormField;
use App\Models\Submission;
use Filament\Forms\Form;
use Filament\Forms;
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
                            $formId = $get('form_id');
                            if (! $formId) {
                                return '-';
                            }
                            $form = \App\Models\Form::withTrashed()->find($formId);
                            if (! $form) {
                                return 'Form not found';
                            }
                            if ($form->trashed()) {
                                return 'Deleted';
                            }
                            if ($form->archived_at) {
                                return 'Archived';
                            }
                            if ($form->published_at) {
                                return 'Published';
                            }
                            return 'Draft';
                        }),
                ])->columns(3),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Forms')
                        ->schema(function (callable $get) {
                            $formId = $get('form_id');
                            if (! $formId) {
                                return [];
                            }
                            $form = \App\Models\Form::with('fields')->find($formId);
                            if (! $form) {
                                return [];
                            }
                            return $form->fields->map(function (FormField $field) {
                                return $field->type->getField($field)
                                    ->label($field->label)
                                    ->required($field->is_required)
                                    ->helperText($field->help_text)
                                    ->default('');
                            })->toArray();
                        }),
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Submission Details')
                        ->schema([
                            Forms\Components\DateTimePicker::make('created_at')
                                ->label('Created At')
                                ->disabled()
                                ->visible(fn($context) => $context === 'edit'),
                        ]),
                ]),
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
                        return $record->values->map(fn($v) => $v->field_name . ': ' . $v->getDisplayValue())->implode(', ');
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
