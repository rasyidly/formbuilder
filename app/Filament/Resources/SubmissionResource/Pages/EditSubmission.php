<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
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

    protected function handleRecordUpdate($record, array $data): Submission
    {
        $values = $data['values'] ?? [];
        unset($data['values']);

        // Update the submission
        $record->update($data);

        // Delete existing values and recreate them
        $record->values()->delete();

        // Create new submission values
        foreach ($values as $valueData) {
            SubmissionValue::create([
                'submission_id' => $record->id,
                'form_field_id' => $valueData['form_field_id'],
                'field_label' => $valueData['field_label'],
                'field_type' => $valueData['field_type'],
                'value' => $valueData['value'],
            ]);
        }

        return $record;
    }
}
