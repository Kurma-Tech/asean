<?php

namespace App\Http\Livewire\Admin\PatentType;

use Livewire\Component;

class TrashedComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.patent-type.trashed-component')->layout('layouts.admin');
    }
}
