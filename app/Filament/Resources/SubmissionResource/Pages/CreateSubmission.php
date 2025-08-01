<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use App\Models\Submission;
use App\Models\SubmissionValue;
use Filament\Resources\Pages\CreateRecord;

class CreateSubmission extends CreateRecord
{
    protected static string $resource = SubmissionResource::class;

    protected function handleRecordCreation(array $data): Submission
    {
        $values = $data['values'] ?? [];
        unset($data['values']);

        // Create the submission first
        $submission = Submission::create($data);

        // Create submission values
        foreach ($values as $valueData) {
            SubmissionValue::create([
                'submission_id' => $submission->id,
                'form_field_id' => $valueData['form_field_id'],
                'field_name' => $valueData['field_name'],
                'field_type' => $valueData['field_type'],
                'value' => $valueData['value'],
            ]);
        }

        return $submission;
    }
}
