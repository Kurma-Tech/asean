<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use Livewire\Component;

class MapComponent extends Component
{
    public $long, $lat, $geoJson, $filter;

    private function loadBusinesses()
    {
        $businesses = Business::filter($this->filter)
                              ->orderBy('created_at', 'DESC')->paginate(2000);

        $renderBusinesses = [];

        foreach($businesses->chunk(50) as $row)
        {
            foreach($row as $business)
            {
                $renderBusinesses[] = [
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
        }

        $geoLocations = [
            'type' => 'FeatureCollection',
            'features' => $renderBusinesses
        ];

        $geoJson = collect($geoLocations)->toJson();
        $this->geoJson = $geoJson;
    }

    public function render()
    {
        $this->loadBusinesses();
        return view('livewire.client.map.map-component');
    }
}
