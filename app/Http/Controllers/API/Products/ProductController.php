<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProduct;
use App\Models\Products;
use App\Models\ProductsVariations;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productsRepository;

    protected $errors;

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
            'hasColorVariation'
        ]);

        $productVariationData = null;

        if($request->input('hasColorVariation')) {
            $productVariationData = $request->input('variations');

        } else {
            $productVariationData = $request->only([
                'first_stock',
                'available_stock',
                'price'
            ]);
        }
        try{
            $product = $this->productsRepository->createProduct($productData, $productVariationData);

            return response()->json($product, 201);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
