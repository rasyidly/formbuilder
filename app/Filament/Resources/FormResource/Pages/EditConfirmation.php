<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditConfirmation extends EditRecord
{
    protected static string $resource = FormResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
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
                ])
            ]);
    }
}
