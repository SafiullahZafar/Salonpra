<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServicePackage;
use App\Models\Service;

class ServicePackageController extends Controller
{
    public function index()
    {
        $packages = ServicePackage::with('services')->latest()->paginate(12);
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        $services = Service::orderBy('name')->get();
        return view('packages.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $package = ServicePackage::create($validated);
        $package->services()->sync($request->input('service_ids', []));

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(ServicePackage $package)
    {
        $services = Service::orderBy('name')->get();
        return view('packages.edit', compact('package', 'services'));
    }

    public function update(Request $request, ServicePackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $package->update($validated);
        $package->services()->sync($request->input('service_ids', []));

        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(ServicePackage $package)
    {
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
