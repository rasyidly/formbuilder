<?php

namespace App\Enums;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

enum FormFieldType: string
{
    case Text = 'text';
    case Email = 'email';
    case Number = 'number';
    case Textarea = 'textarea';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case CheckboxList = 'checkbox_list';
    case File = 'file';
    case Image = 'image';
    case Hidden = 'hidden';

    public function getField(\App\Models\FormField $formField): Component
    {
        return match ($this) {
            self::Text => TextInput::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Email => TextInput::make($formField->id)
                ->label($formField->label)
                ->email()
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Number => TextInput::make($formField->id)
                ->label($formField->label)
                ->numeric()
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Textarea => Textarea::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->rows(3)
                ->required($formField->is_required),

            self::Select => Select::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Radio => Radio::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Checkbox => Checkbox::make($formField->id)
                ->label($formField->label)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::CheckboxList => CheckboxList::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::File, self::Image => FileUpload::make($formField->id)
                ->label($formField->label)
                ->helperText($formField->help_text)
                ->required($formField->is_required)
                ->when($this === self::Image, fn($component) => $component->image()),

            self::Hidden => Hidden::make($formField->id)
                ->default($formField->settings['default_value'] ?? ''),
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Text Input',
            self::Email => 'Email Input',
            self::Number => 'Number Input',
            self::Textarea => 'Textarea',
            self::Select => 'Select Dropdown',
            self::Radio => 'Radio Buttons',
            self::Checkbox => 'Checkbox',
            self::CheckboxList => 'Checkbox List',
            self::File => 'File Upload',
            self::Image => 'Image Upload',
            self::Hidden => 'Hidden Field',
        };
    }

    public function isFileUpload(): bool
    {
        return in_array($this, [self::File, self::Image]);
    }

    public function hasOptions(): bool
    {
        return in_array($this, [self::Select, self::Radio, self::CheckboxList]);
    }

    public static function getOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}
