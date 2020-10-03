<?php


namespace App\Repositories;


use App\Http\Requests\Products\StoreProduct;
use App\Http\Requests\Products\UpdateProduct;
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

                $rules = StoreProduct::variationRules();

                if(! $this->validateColorVariations($variation, $rules)) {
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

    public function updateProduct($id, $productData, $productVariationData)
    {
        
        DB::beginTransaction();

        $product = $this->productsModel->find($id);
        $product->fill($productData);

        if (! $product->save()) {
            throw new \Exception('Cannot update the product', 500);
        }

        if(! Arr::get($productData, 'hasColorVariation')) {
            $productVariation = $this->productsVariationsModel->where('product_id', $id)->first();
            $productVariation->fill($productVariationData);

            if (! $productVariation->save()) {
                DB::rollBack();
                throw new \Exception('Cannot update the product variation', 500);
            }
        } else {
            foreach ($productVariationData as $variation) {

                $rules = UpdateProduct::variationRules();

                if(! $this->validateColorVariations($variation, $rules)) {
                    DB::rollBack();
                    throw new \Exception($this->getErrors(), 422);
                }

                $databaseVariation = $this->productsVariationsModel->where([
                   'product_id' => $id,
                   'color_id' => $variation['color_id']
                ])->first();

                if ($databaseVariation) {
                    $databaseVariation->fill($variation);
                    if(! $databaseVariation->save()) {
                        DB::rollBack();
                        throw new \Exception('Cannot update the product variation', 500);
                    }
                } else {
                    if (! $product->colorVariations()->create($variation)) {
                        DB::rollBack();
                        throw new \Exception('Cannot create the product variation', 500);
                    }
                }
            }
        }

        DB::commit();

        $product->load('colorVariations');

        return $product;
    }

    public function getAllProducts()
    {
        return $this->productsModel->with('colorVariations')->get();
    }

    protected function validateColorVariations($variation, $rules)
    {
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