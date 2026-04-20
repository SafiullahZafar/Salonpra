<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Filter by product type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('product_type', $request->type);
        }

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status !== 'all') {
            switch ($request->stock_status) {
                    case 'low_stock':
                    $query->where('current_stock', '<=', DB::raw('min_stock_level'))
                          ->where('track_inventory', true);
                    break;
                case 'out_of_stock':
                    $query->where('current_stock', '<=', 0)
                          ->where('track_inventory', true);
                    break;
                case 'in_stock':
                    $query->where('current_stock', '>', DB::raw('min_stock_level'))
                          ->where('track_inventory', true);
                    break;
            }
        }

        // Search by name or SKU
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate(15);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('type', 'product')->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'product_type' => 'required|in:retail,service_supply',
            'sku' => 'nullable|string|unique:products,sku',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'track_inventory' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier', 'productUsages.service', 'purchaseItems.purchase']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('type', 'product')->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'product_type' => 'required|in:retail,service_supply',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'track_inventory' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);

        $oldStock = $product->current_stock;

        switch ($validated['adjustment_type']) {
            case 'add':
                $product->addStock($validated['quantity']);
                break;
            case 'subtract':
                $product->deductStock($validated['quantity']);
                break;
            case 'set':
                $product->update(['current_stock' => $validated['quantity']]);
                break;
        }

        // Log the adjustment (you might want to create a stock_adjustment table for this)

        return redirect()->back()->with('success', 'Stock adjusted successfully.');
    }
}
