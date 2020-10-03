<?php


namespace App\Repositories;


use App\Http\Requests\Products\StoreProduct;
use App\Models\Products;
use App\Models\ProductsVariations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsRepository
{
    protected $productsModel;

    protected $productsVariationsModel;

    protected $errors;

    public function __construct(Products $products, ProductsVariations $productsVariations)
    {
        $this->productsModel = $products;
        $this->productsVariationsModel = $productsVariations;
    }

    public function createProduct($productData, $productVariationData)
    {

        DB::beginTransaction();

        $product = $this->productsModel->fill($productData);
        $product->created_by = Auth::user()->id;

        if (! $product->save()) {
            throw new \Exception('Cannot create the product', 500);
        }
        
        if(! Arr::get($productData, 'hasColorVariation')) {
            $productVariation = $this->productsVariationsModel->fill($productVariationData);
            $this->productsVariationsModel->product_id = $product->id;

            if (! $productVariation->save()) {
                DB::rollBack();
                throw new \Exception('Cannot create the product variation', 500);
            }

            DB::commit();
        } else {
            foreach ($productVariationData as $variation) {
                if(! $this->validateColorVariations($variation)) {
                    DB::rollBack();
                    throw new \Exception($this->getErrors(), 422);
                }

                $variation['product_id'] = $product->id;

                if (! $product->colorVariations()->create($variation)) {
                    DB::rollBack();
                    throw new \Exception('Cannot create the product variation', 500);
                }
            }

            DB::commit();
        }

        $product->load('colorVariations');

        return $product;
    }

    protected function validateColorVariations($variation)
    {
        $rules = StoreProduct::variationRules();

        $validator = Validator::make($variation, $rules);

        if($validator->fails()) {
            $this->setErrors($validator->errors()->toJson());

            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors): void
    {
        $this->errors = $errors;
    }
}