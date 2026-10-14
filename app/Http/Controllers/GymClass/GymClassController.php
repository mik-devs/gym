<?php

namespace App\Http\Controllers\GymClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGymClassRequest;
use App\Http\Requests\UpdateGymClassRequest;
use App\Models\GymClass\GymClass;
use App\Models\Trainer\Trainer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class GymClassController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:class.view', only: ['index', 'show']),
            new Middleware('permission:class.create', only: ['create', 'store']),
            new Middleware('permission:class.edit', only: ['edit', 'update']),
            new Middleware('permission:class.delete', only: ['destroy']),
        ];
    }

    public function index(): Response
    {
        return Inertia::render('GymClasses/Index', [
            'classes' => GymClass::query()
                ->with('trainer')
                ->withCount('schedules')
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('GymClasses/Create', [
            'trainers' => Trainer::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreGymClassRequest $request): RedirectResponse
    {
        GymClass::query()->create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function show(GymClass $gymClass): Response
    {
        return Inertia::render('GymClasses/Show', [
            'gymClass' => $gymClass->load([
                'trainer',
                'schedules.enrollments.member',
            ]),
        ]);
    }

    public function edit(GymClass $gymClass): Response
    {
        return Inertia::render('GymClasses/Edit', [
            'gymClass' => $gymClass,
            'trainers' => Trainer::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateGymClassRequest $request, GymClass $gymClass): RedirectResponse
    {
        $gymClass->update($request->validated());

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(GymClass $gymClass): RedirectResponse
    {
        $gymClass->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
