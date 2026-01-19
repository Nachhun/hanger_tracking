<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\Province::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:provinces|string|max:255'
        ]);

        $province = \App\Models\Province::create([
            'name' => $request->name
        ]);

        return response()->json($province, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $id
        ]);

        $province = \App\Models\Province::findOrFail($id);
        $province->update(['name' => $request->name]);

        return response()->json($province);
    }

    public function destroy($id)
    {
        $province = \App\Models\Province::findOrFail($id);
        $province->delete();
        return response()->json(['message' => 'Province deleted']);
    }
}
