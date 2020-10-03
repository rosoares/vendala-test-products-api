<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'hasColorVariation' => 'required|boolean',
            'first_stock' => $this->request->get('hasColorVariation') ? [] : 'required|numeric',
            'available_stock' => $this->request->get('hasColorVariation') ? [] : 'required|numeric',
            'price' => $this->request->get('hasColorVariation') ? [] : 'required|numeric',
            'variations' => $this->request->get('hasColorVariation') ? 'required|array' : []
        ];
    }

    public static function variationRules()
    {
        return [
            'color_id' => 'required|exists:colors,id',
            'first_stock' => 'required|numeric',
            'available_stock' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }
}
