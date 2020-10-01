<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class MercadoColors
{
    protected $list;

    public function getList()
    {
        $url = $this->buildURL();

        $response = Http::get($url);

        $collection = $this->makeCollection($response->body());

        $this->list = $collection
            ->where('id', 'COLOR')
            ->first()
            ->values;
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

}