<?php

namespace App\Http\Livewire\Admin\Journals;

use Livewire\Component;

class JournalsTrashedComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.journals.journals-trashed-component')->layout('layouts.admin');
    }
}
