<?php

namespace App\Enums;

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
    case TEXT = 'text';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case RADIO = 'radio';
    case CHECKBOX = 'checkbox';
    case FILE = 'file';
    case IMAGE = 'image';
    case HIDDEN = 'hidden';

    public function getField(\App\Models\FormField $formField): Component
    {
        return match ($this) {
            self::TEXT => TextInput::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::EMAIL => TextInput::make($formField->id)
                ->label($formField->label)
                ->email()
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::NUMBER => TextInput::make($formField->id)
                ->label($formField->label)
                ->numeric()
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::TEXTAREA => Textarea::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->rows(3)
                ->required($formField->is_required),

            self::SELECT => Select::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::RADIO => Radio::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::CHECKBOX => CheckboxList::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::FILE, self::IMAGE => FileUpload::make($formField->id)
                ->label($formField->label)
                ->helperText($formField->help_text)
                ->required($formField->is_required)
                ->when($this === self::IMAGE, fn($component) => $component->image()),

            self::HIDDEN => Hidden::make($formField->id)
                ->default($formField->settings['default_value'] ?? ''),
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::TEXT => 'Text Input',
            self::EMAIL => 'Email Input',
            self::NUMBER => 'Number Input',
            self::TEXTAREA => 'Textarea',
            self::SELECT => 'Select Dropdown',
            self::RADIO => 'Radio Buttons',
            self::CHECKBOX => 'Checkbox List',
            self::FILE => 'File Upload',
            self::IMAGE => 'Image Upload',
            self::HIDDEN => 'Hidden Field',
        };
    }

    public function isFileUpload(): bool
    {
        return in_array($this, [self::FILE, self::IMAGE]);
    }

    public function hasOptions(): bool
    {
        return in_array($this, [self::SELECT, self::RADIO, self::CHECKBOX]);
    }

    public static function getOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}
