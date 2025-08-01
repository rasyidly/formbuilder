<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use App\Filament\Resources\FormResource\Concerns\ManagesFormFields;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForm extends EditRecord
{
    use ManagesFormFields;

    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing fields into the fields builder
        $fields = $this->record->fields()->orderBy('sequence')->get();

        $fieldsData = [];
        foreach ($fields as $field) {
            $fieldData = [
                'name' => $field->name,
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
                'type' => $field->type,
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
        $this->saveFormFields();
    }

    protected function saveFormFields(): void
    {
        $formFields = array_values($this->data['fields'] ?? []);
        $existingFields = $this->record->fields()->get()->keyBy('sequence');

        // Keep track of sequences that are still being used
        $usedSequences = [];

        foreach ($formFields as $index => $fieldData) {
            $usedSequences[] = $index;

            // Check if field exists at this sequence
            if ($existingFields->has($index)) {
                // Update existing field
                $this->updateFormField($existingFields[$index], $fieldData, $index);
            } else {
                // Create new field
                $this->createFormField($fieldData, $index);
            }
        }

        // Delete fields that are no longer used
        $fieldsToDelete = $existingFields->filter(function ($field) use ($usedSequences) {
            return ! in_array($field->sequence, $usedSequences);
        });

        foreach ($fieldsToDelete as $field) {
            $field->delete();
        }
    }
}
