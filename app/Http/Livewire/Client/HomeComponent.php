<?php

namespace App\Http\Livewire\Client;

use App\Models\Country;
use App\Models\Business;
use App\Models\Journal;
use App\Models\Patent;
use App\Models\IndustryClassification;
use App\Models\JournalCategory;
use App\Models\PatentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class HomeComponent extends Component
{
    use WithPagination;

    public $search = '';
    // public $parent_id;
    // public $industryClass;
    public $country;
    public $countries = []; 
    public $results;
    public $classification;
    public $type = "all";
    public $per_page = 10;
    // private $filters = [];
    // private $filters_all = [];
    // public $businessCountListByCountry = [];
    // public $patentCountListByCountry = [];
    // public $journalCountListByCountry = [];
    // public $countriesNameList = [];
    // public $businessResults = [];
    // public $patentResults = [];

    // protected $listeners = ['resultsUpdated' => 'updatedResults'];

    public function mount()
    {
        // $this->countries = Cache::rememberForever('countries_with_ids', function () {
        //     return json_decode(DB::table('countries')->select('id', 'name')->where("status", "1")->get(), true);
        // });
        // dd($this->countries);
        // ini_set('memory_limit', '-1');
        // $total_business_count = DB::table('businesses')->count();
        // $total_patent_count = DB::table('patents')->count();
        // $this->results = $total_business_count + $total_patent_count;
        // $businessCountByCountry = DB::table('businesses')->select('country_id')->get()->pluck('country_id')->countBy();
        // Country::select('id')->pluck('id')->each(function ($item, $key) use ($businessCountByCountry) {
        //     $this->businessCountListByCountry[$key] = $businessCountByCountry[$item] ?? 0;
        // });
        // $patentCountByCountry = Patent::select('country_id')->pluck('country_id')->countBy();
        // Country::select('id')->pluck('id')->each(function ($item, $key) use ($patentCountByCountry) {
        //     $this->patentCountListByCountry[$key] = $patentCountByCountry[$item] ?? 0;
        // });
        // $journalCountByCountry = Journal::select('country_id')->pluck('country_id')->countBy();
        // Country::select('id')->pluck('id')->each(function ($item, $key) use ($journalCountByCountry) {
        //     $this->journalCountListByCountry[$key] = $journalCountByCountry[$item] ?? 0;
        // });
        // $this->countriesNameList = DB::table('countries')->select('name')->pluck('name');
    }

    public function handleSearch()
    {
        // $this->emit("loader_on");
        // $this->emit("handleSearchEvent", $this->search);
    }

    public function handleFlyOver($long, $lat)
    {
        // $this->emit('flyover', ["long" => $long, "lat" => $lat]);
    }

    public function updatedResults($results)
    {
        // $this->results = $results;
    }

    public function updatedCountry($country)
    {
        // $this->emit("loader_on");
        // $this->country = $country;
        // $this->emit('country_updated', $country);
    }

    public function updatedType($type)
    {
        // $this->emit("loader_on");
        // $this->type = $type;
        // $this->emit('type_updated', $type);
    }

    public function updatedClassification($classification)
    {
        // $this->classification = $classification;
        // $this->emit("loader_on");
        // $this->emit('classification_updated', $classification);
    }

    public function render()
    {
        // $this->emit('updateMap', $this->search);
        // $this->emit('updateReport');

        
        $categories = [];
        if ($this->type == "business") {
            $categories = IndustryClassification::select('id', 'classifications')->where('parent_id', '!=', null)->get();
        }elseif($this->type == "patent") {
            $categories = PatentCategory::select('id', 'classification_category')->where('parent_id', '!=', null)->take(1000)->get();
        }elseif($this->type == "journal") {
            $categories = JournalCategory::select('id', 'category')->where('parent_id', '!=', null)->get();
        }

        return view('livewire.client.home-component', [
            'classifications'  => $categories,
        ])->layout('layouts.client');
    }
}
