<?php

namespace App\Livewire\Components;

use App\Models;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public ?Models\Form $model = null;

    public ?array $data = [];

    public function mount(Models\Form $form): void
    {
        $this->model = $form;
        $this->form->fill();
    }

    public function form(Forms\Form $form): Forms\Form
    {
        $requiredFields = [];

        return $form
            ->columns(1)
            ->statePath('data')
            ->columns(6)
            ->schema([
                ...$requiredFields,
                ...($this->model?->fields->map(function (Models\FormField $field) {
                    return $field->type->getField($field)
                        ->statePath('values.' . $field->id)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->helperText($field->help_text)
                        ->key($field->id)
                        ->columnSpan($field->settings['col_span'] ?? 'full')
                        ->columnStart($field->settings['col_start'] ?? 1);
                })->toArray() ?? [])
            ]);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        // Validation (Filament handles this via form schema, but you can add extra if needed)

        $values = $data['values'] ?? [];
        unset($data['values']);

        // Handle submitter_name and submitter_email if required by settings
        if ($this->model && ($this->model->settings['require_name_input'] ?? false)) {
            $data['submitter_name'] = $this->data['name'] ?? null;
        }
        if ($this->model && ($this->model->settings['require_email_input'] ?? false)) {
            $data['submitter_email'] = $this->data['email'] ?? null;
        }

        $data['form_id'] = $this->model->id;
        $data['submitter_ip'] = request()->ip();
        $data['user_agent'] = request()->userAgent();

        DB::beginTransaction();
        try {
            $submission = Models\Submission::create($data);

            $fields = $this->model->fields->keyBy('id');

            $submissionValues = [];
            foreach ($values as $id => $value) {
                $submissionValues[] = [
                    'submission_id' => $submission->id,
                    'form_field_id' => $id,
                    'field_label' => $fields[$id]->label,
                    'field_type' => $fields[$id]->type,
                    'value' => is_array($value) ? json_encode($value) : $value,
                ];
            }

            Models\SubmissionValue::query()->insert($submissionValues);

            DB::commit();
            session()->flash('success', 'Submission saved successfully!');
            // Optionally, reset form or redirect
            $this->reset('data');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save submission.');
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.components.form');
    }
}
