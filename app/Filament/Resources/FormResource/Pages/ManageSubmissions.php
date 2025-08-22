<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Components as AppComponents;
use App\Models;
use App\Filament\Resources\FormResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageSubmissions extends ManageRelatedRecords
{
    protected static string $resource = FormResource::class;

    protected static string $relationship = 'submissions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Submissions';
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(['lg' => 3])
            ->schema([
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
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
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
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes')
                        ->rows(3)
                        ->placeholder('Enter any additional notes here...')
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('submitter_ip')
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
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::FourExtraLarge)
                    ->fillForm(function ($record) {
                        $data = $record->toArray();
                        $data['values'] = $record->values->mapWithKeys(function ($value) {
                            return [
                                $value->form_field_id => $value->formField->hasOptions() ? json_decode($value->value, true) : $value->value,
                            ];
                        })->toArray();

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
