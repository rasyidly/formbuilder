<?php

namespace App\Filament\Resources\FormResource\Concerns;

use App\Models\FormField;
use Illuminate\Support\Str;

trait ManagesFormFields
{
    /**
     * Upsert form fields for a form (create, update, soft delete removed fields)
     * @param array $fieldsData Array of field data, each must have 'id' if updating
     * @param int $formId
     */
    protected function upsertFormFields(array $fieldsData, int $formId): void
    {
        // Get all current field ids for this form
        $existingFields = FormField::where('form_id', $formId)->get();
        $existingIds = $existingFields->pluck('id')->toArray();
        $incomingIds = collect($fieldsData)->pluck('id')->filter()->toArray();

        // Soft delete fields that are not present in incoming data
        $toDelete = array_diff($existingIds, $incomingIds);
        if (!empty($toDelete)) {
            FormField::whereIn('id', $toDelete)->delete();
        }

        foreach ($fieldsData as $sequence => $fieldData) {
            $fieldType = $fieldData['type'];
            $data = $fieldData['data'];
            $id = $fieldData['id'] ?? null;

            // Prepare options for fields that support them
            $options = null;
            if (in_array($fieldType, ['select', 'radio', 'checkbox']) && isset($data['options'])) {
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
                'is_required' => $data['is_required'] ?? false
            ];

            if ($id) {
                // Update existing field
                $field = FormField::find($id);
                if ($field) {
                    $field->update($fieldPayload);
                    // Restore if soft deleted
                    if (method_exists($field, 'restore') && $field->trashed()) {
                        $field->restore();
                    }
                }
            } else {
                // Create new field
                FormField::create($fieldPayload);
            }
        }
    }

    protected function addTypeSpecificValidationRules(string $fieldType, array $data, array $validationRules): array
    {
        switch ($fieldType) {
            case 'email':
                $validationRules[] = 'email';
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
        $excludedKeys = ['name', 'label', 'placeholder', 'help_text', 'is_required', 'validation_rules', 'options'];

        foreach ($data as $key => $value) {
            if (! in_array($key, $excludedKeys)) {
                $settings[$key] = $value;
            }
        }

        return $settings;
    }
}
