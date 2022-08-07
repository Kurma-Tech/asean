<?php

namespace App\Http\Livewire\Admin\Patent;

use Livewire\Component;

class PatentListComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.patent.patent-list-component')->layout('layouts.admin');
    }
}
