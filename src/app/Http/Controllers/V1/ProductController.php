<?php

namespace App\Http\Controllers\V1;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductFilterRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(ProductFilterRequest $request)
    {
        $filters = new ProductFilter($request);

        $products = $filters
            ->apply(Product::with(['categories']))
            ->orderBy('id', 'desc')
            ->paginate(15);

        return ProductResource::collection($products);
    }

    public function create(ProductCreateRequest $request)
    {
        $validated = $request->validated();

        $categoryIds = $validated['category_ids'] ?? [];

        unset($validated['category_ids']);

        $product = Product::create($validated);

        if (!empty($categoryIds)) {
            $product->categories()->sync($categoryIds);
        }

        return response()->json([
            'data' => $product,
            'message' => 'Product created successfully.',
        ], Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        $product->load('market');

        return response()->json([
            'data' => $product,
        ], Response::HTTP_OK);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validated   = $request->validated();
        $categoryIds = $validated['category_ids'] ?? null;

        unset($validated['category_ids']);

        $product->update($validated);

        if (array_key_exists('category_ids', $request->all())) {
            $product->categories()->sync($categoryIds ?? []);
        }

        return response()->json([
            'data'    => $product,
            'message' => 'Product updated successfully.',
            'categories' => $product->categories()->pluck('categories.id'),
        ], Response::HTTP_OK);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ], Response::HTTP_OK);
    }

    public function addProductCategories(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $product->categories()->syncWithoutDetaching($validated['category_ids']);

        return response()->json([
            'success' => true,
            'message' => 'Categories added to product successfully.',
        ]);
    }

    public function updateCategories(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $product->categories()->sync($validated['category_ids'] ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Product categories updated successfully.',
            'categories' => $product->categories()->pluck('categories.id'),
        ]);
    }
}
