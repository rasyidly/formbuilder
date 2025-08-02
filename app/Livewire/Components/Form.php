<?php

namespace App\Livewire\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->columns(1)
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Form Name')
                    ->required()
                    ->maxLength(255),
                // TextInput::make('title')
                //     ->required(),
                // MarkdownEditor::make('content'),
                // ...
            ]);
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
