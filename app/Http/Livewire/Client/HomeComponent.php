<?php

namespace App\Http\Livewire\Client;

use App\Models\Business;
use App\Models\IndustryClassification;
use Livewire\Component;
use Livewire\WithPagination;

class HomeComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $psic_code;
    public $industryClass;
    public $type;
    public $per_page = 10;
    private $filters = [];
    private $filters_all = [];
    
    public function render()
    {
        if($this->psic_code != '')
        {
            $filter = Business::whereHas('industryClassification', function($q)
            {
                $q->where('psic_code', '=', $this->psic_code);
            });
        } else {
            $filter = Business::filter($this->search);
        }

        $this->filters_all = $filter->get();
        $this->filters = $filter->paginate($this->per_page);

        $this->industryClass = IndustryClassification::distinct()->whereNotNull('psic_code')->get(['psic_code']);

        $this->emit('updateMap', $this->search);
        $this->emit('updateReport');

        return view('livewire.client.home-component', [
            'filters' => $this->filters,
            'filters_all' => $this->filters_all,
            'filter' => $filter,
            'type' => $this->type,
            'industryClass' => $this->industryClass
        ])->layout('layouts.client');
    }
}
