<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->latest()->paginate(16);
        $categories = Category::where('type', 'service')->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'service')->get();

        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'pricing_levels' => 'nullable|array',
            'pricing_levels.*' => 'nullable|numeric|min:0',
            'peak_pricing_enabled' => 'nullable|boolean',
            'peak_price' => 'nullable|numeric|min:0',
            'peak_start' => 'nullable|date_format:H:i',
            'peak_end' => 'nullable|date_format:H:i',
            'is_popular' => 'nullable|boolean',
        ]);

        $validated['pricing_levels'] = array_filter($request->input('pricing_levels', []), function ($value) {
            return $value !== null && $value !== '';
        });

        $validated['peak_pricing_enabled'] = $request->boolean('peak_pricing_enabled');
        $validated['is_popular'] = $request->boolean('is_popular');

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service saved successfully.');
    }

    public function edit(Service $service)
    {
        $categories = Category::where('type', 'service')->get();

        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'pricing_levels' => 'nullable|array',
            'pricing_levels.*' => 'nullable|numeric|min:0',
            'peak_pricing_enabled' => 'nullable|boolean',
            'peak_price' => 'nullable|numeric|min:0',
            'peak_start' => 'nullable|date_format:H:i',
            'peak_end' => 'nullable|date_format:H:i',
            'is_popular' => 'nullable|boolean',
        ]);

        $validated['pricing_levels'] = array_filter($request->input('pricing_levels', []), function ($value) {
            return $value !== null && $value !== '';
        });
        $validated['peak_pricing_enabled'] = $request->boolean('peak_pricing_enabled');
        $validated['is_popular'] = $request->boolean('is_popular');

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service removed successfully.');
    }
}
