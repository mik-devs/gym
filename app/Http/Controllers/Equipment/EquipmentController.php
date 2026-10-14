<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:equipment.view', only: ['index', 'show']),
            new Middleware('permission:equipment.create', only: ['create', 'store']),
            new Middleware('permission:equipment.edit', only: ['edit', 'update']),
            new Middleware('permission:equipment.delete', only: ['destroy']),
        ];
    }

    public function index(): Response
    {
        return Inertia::render('Equipment/Index', [
            'equipment' => Equipment::query()
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Equipment/Create');
    }

    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        Equipment::query()->create($request->validated());

        return redirect()->route('equipment.index')->with('success', 'Equipment created successfully.');
    }

    public function show(Equipment $equipment): Response
    {
        return Inertia::render('Equipment/Show', [
            'equipment' => $equipment,
        ]);
    }

    public function edit(Equipment $equipment): Response
    {
        return Inertia::render('Equipment/Edit', [
            'equipment' => $equipment,
        ]);
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $equipment->update($request->validated());

        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }
}
