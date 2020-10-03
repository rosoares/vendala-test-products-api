<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProduct extends FormRequest
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
        $slug = $this->request->get('slug');

        return [
            'slug' => [
                'sometimes',
                Rule::unique('products')->ignore($slug, 'slug')
            ],
            'name' => 'sometimes|required',
            'hasColorVariation' => 'required|boolean',
            'first_stock' => $this->request->get('hasColorVariation') ? [] : 'sometimes|required|numeric',
            'available_stock' => $this->request->get('hasColorVariation') ? [] : 'sometimes|required|numeric',
            'price' => $this->request->get('hasColorVariation') ? [] : 'sometimes|required|numeric',
            'variations' => $this->request->get('hasColorVariation') ? 'sometimes|required|array' : []
        ];
    }

    public static function variationRules()
    {
        return [
            'color_id' => 'sometimes|required|exists:colors,id',
            'first_stock' => 'sometimes|required|numeric',
            'available_stock' => 'sometimes|required|numeric',
            'price' => 'sometimes|required|numeric',
        ];
    }
}
