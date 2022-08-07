<?php

namespace App\Http\Livewire\Admin\Classification;

use Livewire\Component;

class ClassificationList extends Component
{
    public function render()
    {
        return view('livewire.admin.classification.classification-list')->layout('layouts/admin');
    }
}
