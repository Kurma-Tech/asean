<?php

namespace App\Http\Livewire\Admin\Patent;

use Livewire\Component;

class PatentTrashedComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.patent.patent-trashed-component')->layout('layouts.admin');
    }
}
