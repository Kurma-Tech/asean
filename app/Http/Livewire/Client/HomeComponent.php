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
    public $per_page = 10;
    private $filters = [];
    private $filters_all = [];
    
    public function render()
    {
        $filter = Business::filter($this->search);
        $this->filters_all = $filter->get();
        $this->filters = $filter->paginate($this->per_page);

        $this->emit('updateMap', $this->search);
        $this->emit('updateReport');

        return view('livewire.client.home-component', [
            'filters' => $this->filters,
            'filters_all' => $this->filters_all,
            'filter' => $filter,
            'type' => $this->type
        ])->layout('layouts.client');
    }
}
