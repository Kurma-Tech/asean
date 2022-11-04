<?php

namespace App\Http\Livewire\Admin\PatentKind;

use Livewire\Component;

class TrashedComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.patent-kind.trashed-component')->layout('layouts.admin');
    }
}
