<?php

namespace App\Http\Livewire\Admin;

use App\Models\Business;
use App\Models\Journal;
use App\Models\Patent;
use App\Models\User;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $total_member, $total_business, $total_patent, $total_journals;

    public function render()
    {
        $this->total_member   = User::count();
        $this->total_business = Business::count();
        $this->total_patent   = Patent::count();
        $this->total_journals = Journal::count();
        return view('livewire.admin.dashboard-component' , [
            'total_member' => $this->total_member,
            'total_business' => $this->total_business,
            'total_patent' => $this->total_patent,
            'total_journals' => $this->total_journals
        ])->layout('layouts.admin');
    }
}
