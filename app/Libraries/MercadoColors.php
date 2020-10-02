<?php


namespace App\Libraries;

use App\Models\Colors;
use Illuminate\Support\Facades\Http;

class MercadoColors
{
    protected $list;

    public function getColorsFromMercadoLivre()
    {
        $response  = $this->getAttributesList();

        $collection = $this->makeCollection($response->body());

        $values = $collection
            ->firstWhere('id', 'COLOR')
            ->values;
        
        return  $this->insertColorsToDatabase($values);
    }

    public function getAttributesList()
    {
        $url = $this->buildURL();

        return $response = Http::get($url);
    }

    private function buildURL()
    {
        $categoryID = config('integrations.mercadolivre.category_id');

        return 'https://api.mercadolibre.com/categories/'.$categoryID.'/attributes';
    }

    private function makeCollection($jsonResponse)
    {
        $array = json_decode($jsonResponse);

        return collect($array);
    }

    private function prepareColorsToInsert($values)
    {
        $values = collect($values)->map(function ($item, $key){
           return array(
               'id' => $item->id,
               'name' => $item->name
           );
        });

        return $values->toArray();
    }

    public function insertColorsToDatabase($values)
    {

        $insert = $this->prepareColorsToInsert($values);

        if(! Colors::insert($insert)) {
            return false;
        }
        
        return true;
    }

}