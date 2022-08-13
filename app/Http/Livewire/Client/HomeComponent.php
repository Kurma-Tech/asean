<?php

namespace App\Http\Livewire\Client;

use App\Models\Business;
use Livewire\Component;
use Livewire\WithPagination;

class HomeComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $type;
    
    public function render()
    {
        return view('livewire.client.home-component', [
            'filters' => Business::filter($this->search)
                ->paginate(10),
        ])->layout('layouts.client');
    }
}
