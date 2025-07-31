<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormResource\Pages;
use App\Filament\Resources\FormResource\RelationManagers;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationGroup = 'Forms';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['lg' => 3])
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('General Information')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($set, $state, $context, $get) {
                                    if ($context === 'create') {
                                        $set('slug', Str::slug($get('name')));
                                    }
                                }),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Forms\Components\Textarea::make('description')
                                ->maxLength(65535)
                        ]),
                    Forms\Components\Section::make('Fields')
                        ->schema([
                            Forms\Components\Builder::make('fields')
                                ->blocks([
                                    // [TODO]
                                    Forms\Components\Builder\Block::make('Text Field')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('label')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\Select::make('type')
                                                ->options([
                                                    'text' => 'Text',
                                                    'textarea' => 'Textarea',
                                                    'select' => 'Select',
                                                    'checkbox' => 'Checkbox',
                                                ])
                                                ->default('text')
                                                ->required(),
                                        ]),
                                ]),
                        ])
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Configuration')
                        ->schema([
                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Published At')
                                ->nullable(),
                            Forms\Components\DateTimePicker::make('archived_at')
                                ->label('Archived At')
                                ->nullable(),
                            Forms\Components\Select::make('created_by')
                                ->relationship('creator', 'name')
                                ->searchable()
                                ->default(fn() => Auth::id())
                                ->required(),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('archived_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
