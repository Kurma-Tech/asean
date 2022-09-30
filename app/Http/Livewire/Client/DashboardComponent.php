<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;

class DashboardComponent extends Component
{
    public function render()
    {
        return view('livewire.client.dashboard-component')->layout('layouts.client');
    }
}
