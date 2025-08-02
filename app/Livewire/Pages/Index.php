<?php

namespace App\Livewire\Pages;


use Livewire\Component;
use App\Models\Form;

class Index extends Component
{
    public function render()
    {
        $forms = Form::published()->orderByDesc('published_at')->get();
        return view('livewire.pages.index', [
            'forms' => $forms,
        ]);
    }
}
