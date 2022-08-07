<?php

namespace App\Http\Livewire\Admin\Journals;

use Livewire\Component;

class JournalsListComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.journals.journals-list-component')->layout('layouts.admin');
    }
}
