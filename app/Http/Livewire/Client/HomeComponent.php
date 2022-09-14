<?php

namespace App\Http\Livewire\Client;

use App\Models\Country;
use App\Models\Business;
use App\Models\Patent;
use App\Models\IndustryClassification;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class HomeComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $parent_id;
    public $industryClass;
    public $country;
    public $results;
    public $classification;
    public $type = "all";
    public $per_page = 10;
    private $filters = [];
    private $filters_all = [];

    protected $listeners = ['resultsUpdated' => 'updatedResults'];

    public function mount(){
        $total_business_count = Business::count();
        $total_patent_count = Patent::count();
        $this->results = $total_business_count + $total_patent_count;
    }

    public function updatedResults($results)
    {
        $this->results  = $results;
    }

    public function updatedCountry($country)
    {
        $this->country = $country;
        $this->emit("loader_on");
        $this->emit('country_updated', $country);
    }

    public function updatedType($type)
    {
        $this->type = $type;
        $this->emit("loader_on");
        $this->emit('type_updated', $type);
    }

    public function updatedClassification($classification)
    {
        $this->classification = $classification;
        $this->emit("loader_on");
        $this->emit('classification_updated', $classification);
    }
    
    public function render()
    {
        if($this->parent_id != '')
        {
            $filter = Business::whereHas('industryClassification', function($q)
            {
                $q->where('parent_id', '=', $this->parent_id);
            });
        } else {
            $filter = Business::filter($this->search);
        }

        $this->filters_all = $filter->get();
        $this->filters = $filter->paginate($this->per_page);

        $this->industryClass = IndustryClassification::where('parent_id', Null)->select('id', 'classifications')->get();

        $this->emit('updateMap', $this->search);
        $this->emit('updateReport');

        $countries = Country::where("status", "1")->get();
        $classifications = [];
        if($this->type == "business"){
            $classifications = IndustryClassification::get();
        }

        return view('livewire.client.home-component', [
            'filters' => $this->filters,
            'filters_all' => $this->filters_all,
            'filter' => $filter,
            'countries' => $countries,
            'classifications' => $classifications,
            // 'type' => $this->type,
            'industryClass' => $this->industryClass
        ])->layout('layouts.client');
    }
}
