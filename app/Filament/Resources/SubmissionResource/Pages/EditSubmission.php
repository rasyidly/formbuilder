<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use App\Models\Form;
use App\Models\Submission;
use App\Models\SubmissionValue;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubmission extends EditRecord
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function fillForm(): void
    {
        $record = $this->getRecord()->load('values');

        $data = $record->toArray();
        $data['values'] = $record->values->mapWithKeys(function ($value) {
            return [
                $value->form_field_id => $value->value,
            ];
        })->toArray();

        $this->form->fill($data);
    }

    protected function handleRecordUpdate($record, array $data): Submission
    {
        $values = $data['values'] ?? [];
        unset($data['values']);

        // Update the submission
        $record->update($data);

        // Get form fields info
        $fields = Form::find($record->form_id)->fields->keyBy('id');

        // Prepare upsert data
        $upsertData = [];
        $idsToKeep = [];
        foreach ($values as $id => $value) {
            if (! isset($fields[$id])) {
                continue;
            }
            // Ensure value is a string for DB storage
            $storedValue = is_array($value) ? json_encode($value) : $value;
            $upsertData[] = [
                'submission_id' => $record->id,
                'form_field_id' => $id,
                'field_label' => $fields[$id]->label,
                'field_type' => $fields[$id]->type,
                'value' => $storedValue,
            ];
            $idsToKeep[] = $id;
        }

        if (! empty($upsertData)) {
            SubmissionValue::upsert(
                $upsertData,
                ['submission_id', 'form_field_id'],
                ['field_label', 'field_type', 'value']
            );
            // Delete stale values
            SubmissionValue::where('submission_id', $record->id)
                ->whereNotIn('form_field_id', $idsToKeep)
                ->delete();
        } else {
            // If no values, delete all
            SubmissionValue::where('submission_id', $record->id)->delete();
        }

        return $record;
    }
}
