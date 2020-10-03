<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProduct;
use App\Models\Products;
use App\Models\ProductsVariations;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->middleware('auth:api');
        $this->productsRepository = $productsRepository;
    }

    public function store(StoreProduct $request)
    {
        $productData = $request->only([
            'name',
            'description',
            'slug',
        ]);

        $productVariationData = $request->only([
            'first_stock',
            'available_stock',
            'price'
        ]);

        // TODO color variation

        try{
            $product = $this->productsRepository->createProduct($productData, $productVariationData);

            return response()->json($product, 201);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

    }
}
