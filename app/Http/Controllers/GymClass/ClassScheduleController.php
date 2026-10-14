<?php

namespace App\Http\Controllers\GymClass;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassScheduleRequest;
use App\Http\Requests\UpdateClassScheduleRequest;
use App\Models\GymClass\ClassSchedule;
use App\Models\GymClass\GymClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class ClassScheduleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:class.view', only: ['index']),
            new Middleware('permission:class.create', only: ['create', 'store']),
            new Middleware('permission:class.edit', only: ['edit', 'update']),
            new Middleware('permission:class.delete', only: ['destroy']),
        ];
    }

    public function index(): Response
    {
        return Inertia::render('ClassSchedules/Index', [
            'schedules' => ClassSchedule::query()
                ->with('gymClass')
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('ClassSchedules/Create', [
            'classes' => GymClass::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreClassScheduleRequest $request): RedirectResponse
    {
        ClassSchedule::query()->create($request->validated());

        return redirect()->route('class-schedules.index')->with('success', 'Class schedule created successfully.');
    }

    public function edit(ClassSchedule $classSchedule): Response
    {
        return Inertia::render('ClassSchedules/Edit', [
            'schedule' => $classSchedule->load('gymClass'),
            'classes' => GymClass::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateClassScheduleRequest $request, ClassSchedule $classSchedule): RedirectResponse
    {
        $classSchedule->update($request->validated());

        return redirect()->route('class-schedules.index')->with('success', 'Class schedule updated successfully.');
    }

    public function destroy(ClassSchedule $classSchedule): RedirectResponse
    {
        $classSchedule->delete();

        return redirect()->route('class-schedules.index')->with('success', 'Class schedule deleted successfully.');
    }
}
