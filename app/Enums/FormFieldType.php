<?php

namespace App\Enums;

use App\Models\FormField;
use Filament\Forms\Components;

enum FormFieldType: string
{
    case Text = 'text';
    case Email = 'email';
    case Number = 'number';
    case Date = 'date';
    case Time = 'time';
    case DateTime = 'datetime';
    case Textarea = 'textarea';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case CheckboxList = 'checkbox_list';
    case File = 'file';
    case Image = 'image';
    case Hidden = 'hidden';

    public function getField(FormField $formField): Components\Component
    {
        return match ($this) {
            self::Text => Components\TextInput::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Email => Components\TextInput::make($formField->id)
                ->label($formField->label)
                ->email()
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Number => Components\TextInput::make($formField->id)
                ->label($formField->label)
                ->numeric()
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Textarea => Components\Textarea::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->rows(3)
                ->required($formField->is_required),

            self::Select => Components\Select::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Radio => Components\Radio::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Checkbox => Components\Checkbox::make($formField->id)
                ->label($formField->label)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::CheckboxList => Components\CheckboxList::make($formField->id)
                ->label($formField->label)
                ->options($formField->getOptionsArray())
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::File, self::Image => Components\FileUpload::make($formField->id)
                ->label($formField->label)
                ->helperText($formField->help_text)
                ->required($formField->is_required)
                ->when($this === self::Image, fn ($component) => $component->image()),

            self::Date => Components\DatePicker::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Time => Components\TimePicker::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::DateTime => Components\DateTimePicker::make($formField->id)
                ->label($formField->label)
                ->placeholder($formField->placeholder)
                ->helperText($formField->help_text)
                ->required($formField->is_required),

            self::Hidden => Components\Hidden::make($formField->id)
                ->default($formField->settings['default_value'] ?? ''),
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Text Input',
            self::Email => 'Email Input',
            self::Number => 'Number Input',
            self::Date => 'Date Input',
            self::Time => 'Time Input',
            self::DateTime => 'Datetime Input',
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
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}
