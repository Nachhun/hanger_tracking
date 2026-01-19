<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Get all brands (admin)
    public function index(Request $request)
    {
        $query = Brand::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->active);
        }

        $brands = $query->latest()->get();

        return response()->json($brands);
    }

    // Get only active brands (for salesmen)
    public function active()
    {
        $brands = Brand::active()->orderBy('name')->get();
        return response()->json($brands);
    }

    // Create new brand (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $brand = Brand::create($validated);

        return response()->json([
            'message' => 'Brand created successfully',
            'brand' => $brand,
        ], 201);
    }

    // Update brand (admin)
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $brand->update($validated);

        return response()->json([
            'message' => 'Brand updated successfully',
            'brand' => $brand,
        ]);
    }

    // Delete brand (admin)
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Check if brand is used in any entries
        if ($brand->hangerEntries()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete brand that is used in entries',
            ], 400);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully',
        ]);
    }
}
