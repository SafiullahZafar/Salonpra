<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'type' => 'required|in:service,product',
            ]);

            $category = Category::create([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name),
                'type' => $request->type,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'category' => $category
                ]);
            }

            return back()->with('success', 'Category created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again.'
                ], 500);
            }
            throw $e;
        }
    }
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            ]);

            $category->update([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name),
            ]);

            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cannot delete this category. It may be in use by services.'], 400);
        }
    }
}
