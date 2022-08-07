<?php

namespace App\Http\Livewire\Admin\Patent;

use Livewire\Component;

class PatentAddComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.patent.patent-add-component')->layout('layouts.admin');
    }
}
