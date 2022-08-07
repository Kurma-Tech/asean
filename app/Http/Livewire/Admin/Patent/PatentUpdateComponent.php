<?php

namespace App\Http\Livewire\Admin\Patent;

use Livewire\Component;

class PatentUpdateComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.patent.patent-update-component')->layout('layouts.admin');
    }
}
