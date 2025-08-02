<?php

namespace App\Livewire\Pages\Forms;

use App\Models\Form;
use Livewire\Component;

class Show extends Component
{
    public ?Form $form = null;

    public function mount(string $slug): void
    {
        $this->form = Form::with('fields')->published()->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.pages.forms.show');
    }
}
