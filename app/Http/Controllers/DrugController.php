<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        $query = Drug::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($formulation = $request->input('formulation')) {
            $query->where('formulation', $formulation);
        }

        if ($status = $request->input('status')) {
            match ($status) {
                'low'      => $query->whereRaw('total_quantity <= reorder_level AND total_quantity > 0'),
                'out'      => $query->where('total_quantity', 0),
                'adequate' => $query->whereRaw('total_quantity > reorder_level'),
                default    => null,
            };
        }

        $drugs       = $query->where('is_active', true)->orderBy('name')->paginate(20)->withQueryString();
        $categories  = Drug::categories();
        $formulations = Drug::formulations();

        return view('drugs.index', compact('drugs', 'categories', 'formulations'));
    }

    public function create()
    {
        $categories   = Drug::categories();
        $formulations = Drug::formulations();
        return view('drugs.create', compact('categories', 'formulations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'brand_name'      => ['nullable', 'string', 'max:255'],
            'category'        => ['required', 'string'],
            'formulation'     => ['required', 'string'],
            'strength'        => ['nullable', 'string', 'max:100'],
            'unit_of_measure' => ['required', 'string', 'max:100'],
            'reorder_level'   => ['required', 'integer', 'min:1'],
            'notes'           => ['nullable', 'string'],
        ]);

        Drug::create($data);

        return redirect()->route('drugs.index')
            ->with('success', "Drug '{$data['name']}' has been registered successfully.");
    }

    public function show(Drug $drug)
    {
        $drug->load(['stockBatches.supplier', 'dispensingRecords' => function ($q) {
            $q->with('dispensedBy')->orderByDesc('dispensed_date')->limit(20);
        }]);

        return view('drugs.show', compact('drug'));
    }

    public function edit(Drug $drug)
    {
        $categories   = Drug::categories();
        $formulations = Drug::formulations();
        return view('drugs.edit', compact('drug', 'categories', 'formulations'));
    }

    public function update(Request $request, Drug $drug)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'brand_name'      => ['nullable', 'string', 'max:255'],
            'category'        => ['required', 'string'],
            'formulation'     => ['required', 'string'],
            'strength'        => ['nullable', 'string', 'max:100'],
            'unit_of_measure' => ['required', 'string', 'max:100'],
            'reorder_level'   => ['required', 'integer', 'min:1'],
            'notes'           => ['nullable', 'string'],
            'is_active'       => ['boolean'],
        ]);

        $drug->update($data);

        return redirect()->route('drugs.index')
            ->with('success', "Drug '{$drug->name}' updated successfully.");
    }

    public function destroy(Drug $drug)
    {
        $drug->update(['is_active' => false]);
        return redirect()->route('drugs.index')
            ->with('success', "Drug '{$drug->name}' has been deactivated.");
    }
}
