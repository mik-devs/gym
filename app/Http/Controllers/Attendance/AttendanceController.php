<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance\Attendance;
use App\Models\Member\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:attendance.view', only: ['index', 'show']),
            new Middleware('permission:attendance.create', only: ['create', 'store']),
            new Middleware('permission:attendance.edit', only: ['edit', 'update', 'checkOut']),
            new Middleware('permission:attendance.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $date = $request->date('date')?->toDateString() ?? today()->toDateString();

        return Inertia::render('Attendances/Index', [
            'attendances' => Attendance::query()
                ->with('member')
                ->whereDate('check_in_at', $date)
                ->latest('check_in_at')
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['date' => $date],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Attendances/Create', [
            'members' => Member::query()
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['check_in_at'] ??= now();

        Attendance::query()->create($data);

        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance): Response
    {
        return Inertia::render('Attendances/Show', [
            'attendance' => $attendance->load('member'),
        ]);
    }

    public function edit(Attendance $attendance): Response
    {
        return Inertia::render('Attendances/Edit', [
            'attendance' => $attendance,
            'members' => Member::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $attendance->update($request->validated());

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    public function checkOut(Attendance $attendance): RedirectResponse
    {
        $attendance->update(['check_out_at' => now()]);

        return redirect()->route('attendances.index')->with('success', 'Member checked out successfully.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }
}
