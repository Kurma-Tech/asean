<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use App\Models\Patent;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MapComponent extends Component
{
    public $long, $lat, $geoJson, $type, $patentJson;
    protected $filters = [];
    protected $patents = [];

    protected $listeners = ['updateMap' => 'updateMapModel', 'type_updated' => 'updatedType'];

    public function updateMapModel($searchString)
    {
        $this->filters = Business::filter($searchString)->get();
        $this->loadJsonData();
    }

    public function mount($type)
    {
        $this->filters = Business::get();
        $this->patents = Patent::get();
        $this->type = $type;
        
        if($type == "Laos"){
            $this->geoJson = collect([])->toJson();
            $this->patentJson = collect([])->toJson();
        }else{
            $this->loadJsonData();
        }
        // $this->emit("mapUpdated");
        $this->emit("mapUpdated", ["geoJson" => $this->geoJson, "patentJson" => $this->patentJson]);
        
    }

    public function updatedType($type)
    {
        $this->type = $type;
        $this->mount($type);
    }

    private function loadJsonData()
    {
        $data = [];
        $data = $this->filters;

        $renderData = [];
        $patentData = [];

        // foreach($data->chunk(100) as $row)
        // {
        // Business
        foreach ($data as $business) {
            $renderData[] = [
                'type' => 'Feature',
                'geometry' => [
                    'coordinates' => [$business->long, $business->lat],
                    'type' => 'Point',
                ],
                'properties' => [
                    'locationId' => $business->id,
                    'company_name' => $business->company_name ?? 'No Data',
                    'date_registerd' => $business->date_registered ?? 'No Data',
                    'ngc_code' => $business->ngc_code ?? 'No Data',
                    'address' => $business->address ?? 'No Data',
                    'business_type' => $business->businessType->type ?? 'No Data',
                    'industry_classification' => $business->industryClassification->classifications ?? 'No Data',
                    'industry_description' => $business->industry_description ?? 'No Data',
                ]
            ];
        }
        // }

        foreach ($this->patents as $patent) {
            $patentData[] = [
                'type' => 'Feature',
                'geometry' => [
                    'coordinates' => [$patent->long, $patent->lat],
                    'type' => 'Point',
                ],
                'properties' => [
                    'id' => $patent->id,
                    'patent_id' => $patent->patent_id,
                    'title' => $patent->title ?? 'No Data',
                    'date_registerd' => $patent->date ?? 'No Data'
                ]
            ];
        }

        $geoLocations = [
            'type' => 'FeatureCollection',
            'features' => $renderData
        ];

        $patentGeoLocations = [
            'type' => 'FeatureCollection',
            'features' => $patentData
        ];

        $geoJson = collect($geoLocations)->toJson();
        $this->geoJson = $geoJson;
        $patentJson = collect($patentGeoLocations)->toJson();
        $this->patentJson = $patentJson;

        // $this->emit("mapUpdated", ["businessData" => $geoLocations, "patentData" => $patentGeoLocations]);
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
