<?php

namespace App\Filament\Resources;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\FormResource\Pages;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Form;
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
                Forms\Components\Tabs::make()
                    ->columnSpan(['lg' => 2])
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-information-circle')
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
                            ]),
                        Forms\Components\Tabs\Tab::make('Confirmation')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Forms\Components\TextInput::make('settings.redirection_url')
                                    ->label('Redirection URL')
                                    ->helperText('If URLs are provided, users will be redirected to the specified URL(s) after submitting the form.')
                                    ->url(),
                                Forms\Components\Textarea::make('settings.submitted_message')
                                    ->label('Confirmation Message')
                                    ->helperText('This message will be displayed to users after they submit the form.'),
                                Forms\Components\TextInput::make('settings.submit_label')
                                    ->label('Submit Button Label')
                                    ->default('Submit')
                                    ->helperText('This label will be displayed on the form submission button.'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Notifications')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Forms\Components\TextInput::make('settings.notification_subject')
                                    ->label('Email Subject')
                                    ->default('New Form Submission')
                                    ->helperText('This subject will be used for the notification emails.'),
                                Forms\Components\Repeater::make('settings.recipient_emails')
                                    ->label('Email addresses to receive form submissions')
                                    ->addActionLabel('Add recipient email')
                                    ->simple(
                                        Forms\Components\TextInput::make('email')
                                            ->placeholder('Enter email addresses')
                                            ->required()
                                    ),
                            ]),
                    ]),
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
