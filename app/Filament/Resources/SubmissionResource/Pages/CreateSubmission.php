<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use App\Models\Form;
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

        $data['submitter_ip'] = request()->ip();
        $data['user_agent'] = request()->userAgent();

        // Create the submission first
        $submission = Submission::create($data);

        $fields = Form::find($data['form_id'])->fields->keyBy('id');

        // Create submission values
        foreach ($values as $id => $value) {
            SubmissionValue::create([
                'submission_id' => $submission->id,
                'form_field_id' => $id,
                'field_label' => $fields[$id]->label,
                'field_type' => $fields[$id]->type,
                'value' => $value,
            ]);
        }

        return $submission;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
