<?php

namespace App\Livewire\Components;

use App\Models;
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
        return $form
            ->columns(1)
            ->statePath('data')
            ->schema(
                $this->model?->fields->map(function (Models\FormField $field) {
                    return $field->type->getField($field)
                        ->statePath('values.' . $field->id)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->helperText($field->help_text)
                        ->key($field->id);
                })->toArray() ?? []
            );
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.components.form');
    }
}
