<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $validated = $request->validate([
            'market_id'     => ['required', 'exists:markets,id'],
        ]);

        return response()->json([
            'success' => true,
            'data' => Category::where('market_id', $validated['market_id'])->orderBy('id', 'DESC')->get()
        ], Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:categories,name'],
            'market_id' => ['required', 'exists:markets,id'],
        ]);

        $category = Category::create([
            'market_id' => $validated['market_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json([
            'success' => true,
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => $category
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:categories,name,' . $category->id],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        return response()->json([
            'success' => true,
            'data' => $category
        ], Response::HTTP_OK);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted.'
        ], Response::HTTP_OK);
    }
}
