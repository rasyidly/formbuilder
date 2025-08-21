<?php

namespace App\Filament\Resources\FormResource\Concerns;

use App\Models\FormField;

trait ManagesFormFields
{
    protected function upsertFormFields(array $fieldsData, int $formId): void
    {
        // Get all current fields for this form
        $existingFields = FormField::where('form_id', $formId)->get();
        $existingFieldsById = $existingFields->keyBy('id');
        $processedIds = [];

        foreach ($fieldsData as $sequence => $fieldData) {
            $fieldType = $fieldData['type'];
            $data = $fieldData['data'];
            $id = $fieldData['data']['id'] ?? null;

            // Prepare options for fields that support them
            $options = null;
            if (in_array($fieldType, ['select', 'radio', 'checkbox_list']) && isset($data['options'])) {
                $options = $data['options'];
            }

            // Prepare validation rules
            $validationRules = [];
            if (isset($data['validation_rules'])) {
                $validationRules = is_array($data['validation_rules'])
                    ? $data['validation_rules']
                    : array_filter(explode(',', $data['validation_rules']));
            }

            // Add type-specific validation rules
            $validationRules = $this->addTypeSpecificValidationRules($fieldType, $data, $validationRules);

            // Prepare field settings
            $settings = $this->prepareFieldSettings($data);

            $fieldPayload = [
                'form_id' => $formId,
                'sequence' => $sequence,
                'label' => $data['label'],
                'type' => $fieldType,
                'placeholder' => $data['placeholder'] ?? null,
                'help_text' => $data['help_text'] ?? null,
                'options' => $options ? array_values($options) : null,
                'validation_rules' => $validationRules,
                'conditional_logic' => null, // TODO: Implement conditional logic
                'settings' => $settings,
                'is_required' => $data['is_required'] ?? false,
            ];

            if ($id && isset($existingFieldsById[$id]) && $existingFieldsById[$id]->form_id == $formId) {
                // Update existing field (only if it belongs to this form)
                $field = $existingFieldsById[$id];
                $field->update($fieldPayload);
                // Restore if soft deleted
                if (method_exists($field, 'restore') && $field->trashed()) {
                    $field->restore();
                }
                $processedIds[] = $field->id;
            } elseif (!$id) {
                // Create new field (no id means new)
                $newField = FormField::create($fieldPayload);
                $processedIds[] = $newField->id;
            }
            // If $id is set but not found in $existingFieldsById, skip (do not create new with that id)
        }

        // Soft delete fields that were not processed (not present in incoming data)
        $toDelete = $existingFields->pluck('id')->diff($processedIds)->all();
        if (!empty($toDelete)) {
            FormField::whereIn('id', $toDelete)->delete();
        }
    }

    protected function addTypeSpecificValidationRules(string $fieldType, array $data, array $validationRules): array
    {
        switch ($fieldType) {
            case 'email':
                $validationRules = ['email'];
                break;
            case 'number':
                $validationRules[] = 'numeric';
                if (isset($data['min'])) {
                    $validationRules[] = 'min:' . $data['min'];
                }
                if (isset($data['max'])) {
                    $validationRules[] = 'max:' . $data['max'];
                }
                break;
            case 'file':
                $validationRules[] = 'file';
                if (isset($data['accepted_file_types']) && ! empty($data['accepted_file_types'])) {
                    $mimes = is_array($data['accepted_file_types'])
                        ? implode(',', $data['accepted_file_types'])
                        : $data['accepted_file_types'];
                    $validationRules[] = 'mimes:' . $mimes;
                }
                if (isset($data['max_file_size'])) {
                    $validationRules[] = 'max:' . ($data['max_file_size'] * 1024); // Convert MB to KB
                }
                break;
            case 'date':
                $validationRules[] = 'date';
                if (isset($data['min_date'])) {
                    $validationRules[] = 'after_or_equal:' . $data['min_date'];
                }
                if (isset($data['max_date'])) {
                    $validationRules[] = 'before_or_equal:' . $data['max_date'];
                }
                break;
            case 'time':
                $validationRules[] = 'date_format:H:i' . (isset($data['seconds']) && $data['seconds'] ? ':s' : '');
                break;
        }

        return $validationRules;
    }

    protected function prepareFieldSettings(array $data): array
    {
        $settings = [];
        $excludedKeys = ['id', 'name', 'label', 'placeholder', 'help_text', 'is_required', 'validation_rules', 'options'];

        foreach ($data as $key => $value) {
            if (! in_array($key, $excludedKeys)) {
                $settings[$key] = $value;
            }
        }

        return $settings;
    }
}
