<?php

namespace App\Filament\Resources;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\FormResource\Pages;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationGroup = 'Forms';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['lg' => 5])
            ->schema([
                Forms\Components\Section::make('Fields')
                    ->columnSpan(['lg' => 3])
                    ->schema([
                        Forms\Components\Builder::make('fields')
                            ->blocks(AppComponents\Forms\BlockFields\FormBlockRegistry::getAllBlocks())
                            ->addActionLabel('Add Field')
                            ->blockIcons()
                            ->reorderable()
                            ->collapsible()
                            ->blockNumbers(false)
                            ->cloneable()
                            ->hiddenLabel()
                            ->blockPickerColumns(['lg' => 2])
                            ->minItems(1)
                    ]),
                Forms\Components\Section::make('General')
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, string $operation, Forms\Get $get) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($get('name')));
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Toggle::make('published_at')
                            ->label('Mark form as published and accessible')
                            ->default(true),
                        Forms\Components\RichEditor::make('description')
                            ->label('Form Description')
                            ->maxLength(10000),
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
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if ($record->archived_at) {
                            return 'Archived';
                        }
                        if ($record->published_at) {
                            return 'Published';
                        }

                        return 'Draft';
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Published' => 'success',
                        'Archived' => 'gray',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('archived_at')
                    ->dateTime()
                    ->sortable(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditForm::class,
            Pages\EditConfirmation::class,
            Pages\ManageNotifications::class,
            Pages\ManageSubmissions::class
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
            'confirmation' => Pages\EditConfirmation::route('/{record}/confirmation'),
            'notifications' => Pages\ManageNotifications::route('/{record}/notifications'),
            'submissions' => Pages\ManageSubmissions::route('/{record}/submissions'),
        ];
    }
}
