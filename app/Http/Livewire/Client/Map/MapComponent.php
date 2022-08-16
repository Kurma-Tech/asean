<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use Livewire\Component;

class MapComponent extends Component
{
    public $long, $lat, $geoJson, $type;
    protected $filters = [];

    protected $listeners = ['updateMap' => 'updateMapModel'];
 
    public function updateMapModel($searchString)
    {
        $this->filters = Business::filter($searchString)->get();
        $this->loadJsonData();
        
    }

    public function mount($type)
    {
        $this->filters = Business::get();
        $this->type = $type;
        $this->loadJsonData();
    }

    private function loadJsonData()
    {
        $data = [];
        $data = $this->filters;

        $renderData = [];

        // foreach($data->chunk(100) as $row)
        // {
            // Business
            foreach($data as $business)
            {
                $renderData[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'coordinates' => [$business->long, $business->lat],
                        'type' => 'Point',
                    ],
                    'properties' => [
                        'locationId' => $business->id,
                        'company_name' => $business->company_name ?? 'No Data',
                        'date_registerd'=> $business->date_registered ?? 'No Data',
                        'ngc_code' => $business->ngc_code ?? 'No Data',
                        'address' => $business->address ?? 'No Data',
                        'business_type' => $business->businessType->type ?? 'No Data',
                        'industry_classification' => $business->industryClassification->classifications ?? 'No Data',
                        'industry_description' => $business->industry_description ?? 'No Data',
                    ]
                ];
            }
        // }

        $geoLocations = [
            'type' => 'FeatureCollection',
            'features' => $renderData
        ];

        $geoJson = collect($geoLocations)->toJson();
        $this->geoJson = $geoJson;

        $this->emit("mapUpdated", $geoLocations);
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
