<?php

namespace App\Http\Livewire\Admin\Business;

use Livewire\Component;

class BusinessTrashedComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.business.business-trashed-component')->layout('layouts.admin');
    }
}
