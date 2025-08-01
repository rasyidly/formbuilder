<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use App\Filament\Resources\FormResource\Concerns\ManagesFormFields;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditForm extends EditRecord
{
    use ManagesFormFields;

    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label(__('View form'))
                ->url(fn() => route('forms.show', ['slug' => $this->record->slug]))
                ->icon('heroicon-o-eye')
                ->openUrlInNewTab()
                ->color('gray'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $fields = $this->record->fields()->orderBy('sequence')->get();

        $fieldsData = [];
        foreach ($fields as $field) {
            $fieldData = [
                'name' => Str::snake($field->label),
                'label' => $field->label,
                'placeholder' => $field->placeholder,
                'help_text' => $field->help_text,
                'is_required' => $field->is_required,
                'validation_rules' => $field->validation_rules,
            ];

            // Add type-specific data
            if ($field->hasOptions()) {
                $fieldData['options'] = $field->options;
            }

            // Merge settings
            if ($field->settings) {
                $fieldData = array_merge($fieldData, $field->settings);
            }

            $fieldsData[] = [
                'type' => $field->type->value,
                'data' => $fieldData,
            ];
        }

        $data['fields'] = $fieldsData;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove fields from form data as they will be saved separately
        unset($data['fields']);

        return $data;
    }

    protected function afterSave(): void
    {
        $formFields = array_values($this->data['fields'] ?? []);

        $this->upsertFormFields($formFields, $this->record->id);
    }
}
