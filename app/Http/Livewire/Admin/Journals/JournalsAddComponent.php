<?php

namespace App\Http\Livewire\Admin\Journals;

use Livewire\Component;

class JournalsAddComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.journals.journals-add-component')->layout('layouts.admin');
    }
}
