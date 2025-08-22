<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Components as AppComponents;
use App\Enums\FormFieldType;
use App\Filament\Resources\FormResource;
use App\Models\FormField;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\Data\MentionItem;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageNotifications extends ManageRelatedRecords
{
    protected static string $resource = FormResource::class;

    protected static string $relationship = 'notifications';

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationLabel(): string
    {
        return 'Notifications';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('recipient_type')
                    ->options([
                        'custom' => 'Custom Email Addresses',
                        'submitter' => 'Form Submitter (From Field)',
                    ])
                    ->default('custom')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state === 'custom') {
                            $set('field_key_id', null);
                        } else {
                            $set('recipients', []);
                        }
                    }),
                Forms\Components\Repeater::make('recipients')
                    ->label('Recipients')
                    ->simple(
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->placeholder('Enter email address'),
                    )
                    ->minItems(1)
                    ->addActionLabel('Add Recipient')
                    ->hint('Add one or more email addresses to receive this notification')
                    ->visible(fn(Get $get) => $get('recipient_type') === 'custom')
                    ->required(fn(Get $get) => $get('recipient_type') === 'custom'),
                Forms\Components\Select::make('field_key_id')
                    ->label('Email Field')
                    ->placeholder('Select the email field to send thank you email to submitters')
                    ->helperText('This will send a thank you email to the submitter using their email from the selected field')
                    ->options(function () {
                        return $this->getOwnerRecord()
                            ->fields()
                            ->where('type', FormFieldType::Email)
                            ->pluck('label', 'id')
                            ->toArray();
                    })
                    ->visible(fn(Get $get) => $get('recipient_type') === 'submitter')
                    ->required(fn(Get $get) => $get('recipient_type') === 'submitter'),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Email subject line'),
                TiptapEditor::make('body')
                    ->profile('simple')
                    ->placeholder('Email body content...')
                    ->helperText('Use @ to add field value')
                    ->mentionItems($this->getOwnerRecord()->fields->map(fn(FormField $field) => new MentionItem(
                        label: $field->label,
                        id: $field->id
                    ))->toArray())
                    ->extraInputAttributes(['style' => 'min-height: 10rem;']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient_type')
                    ->label('Type')
                    ->getStateUsing(function ($record) {
                        if ($record->field_key_id) {
                            return 'Form Submitter';
                        }
                        return 'Custom Recipients';
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Form Submitter' => 'success',
                        'Custom Recipients' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('recipients_display')
                    ->label('Recipients')
                    ->getStateUsing(function ($record) {
                        if ($record->field_key_id) {
                            $field = FormField::find($record->field_key_id);
                            return $field ? "Submitters via '{$field->label}' field" : 'Email field not found';
                        }
                        if (!empty($record->recipients)) {
                            return implode(', ', array_slice($record->recipients, 0, 2)) .
                                (count($record->recipients) > 2 ? ' +' . (count($record->recipients) - 2) . ' more' : '');
                        }
                        return 'No recipients';
                    })
                    ->limit(50),
                AppComponents\Columns\CreatedAtColumn::make(),
                AppComponents\Columns\LastModifiedColumn::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('recipient_type')
                    ->options([
                        'custom' => 'Custom Recipients',
                        'submitter' => 'Form Submitter',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        return $data['value'] === 'submitter'
                            ? $query->whereNotNull('field_key_id')
                            : $query->whereNull('field_key_id');
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->fillForm(function (FormNotification $record): array {
                        return [
                            'recipient_type' => $record->isForSubmitters() ? 'submitter' : 'custom',
                            ...$record->toArray(),
                        ];
                    }),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
