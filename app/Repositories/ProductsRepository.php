<?php


namespace App\Repositories;


use App\Models\Products;
use App\Models\ProductsVariations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsRepository
{
    protected $productsModel;

    protected $productsVariationsModel;

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

        $productVariation = $this->productsVariationsModel->fill($productVariationData);
        $this->productsVariationsModel->product_id = $product->id;

        if (! $productVariation->save()) {
            DB::rollBack();
            throw new \Exception('Cannot create the product variation', 500);
        }

        DB::commit();

        $product->load('colorVariations');

        return $product;
    }
}