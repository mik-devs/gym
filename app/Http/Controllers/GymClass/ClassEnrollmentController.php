<?php

namespace App\Http\Controllers\GymClass;

use App\Http\Controllers\Controller;
use App\Models\GymClass\ClassEnrollment;
use App\Models\GymClass\ClassSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class ClassEnrollmentController extends Controller implements HasMiddleware
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
            new Middleware('permission:class.create', only: ['store']),
            new Middleware('permission:class.edit', only: ['update']),
            new Middleware('permission:class.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $query = ClassEnrollment::query()->with(['classSchedule.gymClass', 'member']);
        $scheduleId = $request->integer('class_schedule_id');

        if ($scheduleId > 0) {
            $query->where('class_schedule_id', $scheduleId);
        }

        return Inertia::render('ClassEnrollments/Index', [
            'enrollments' => $query->latest('id')->paginate(10)->withQueryString(),
            'filters' => ['class_schedule_id' => $scheduleId ?: null],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_schedule_id' => ['required', 'exists:class_schedules,id'],
            'member_id' => ['required', 'exists:members,id'],
            'status' => ['required', 'in:enrolled,cancelled,waitlist,attended'],
        ]);

        $alreadyEnrolled = ClassEnrollment::query()
            ->where('class_schedule_id', $data['class_schedule_id'])
            ->where('member_id', $data['member_id'])
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'This member already has an enrollment for the selected schedule.');
        }

        if ($data['status'] === 'enrolled') {
            $schedule = ClassSchedule::query()
                ->with('gymClass')
                ->findOrFail((int) $data['class_schedule_id']);
            $enrolledCount = ClassEnrollment::query()
                ->where('class_schedule_id', $schedule->id)
                ->where('status', 'enrolled')
                ->count();

            if ($enrolledCount >= $schedule->gymClass->capacity) {
                return back()->with('error', 'This class schedule is at capacity.');
            }
        }

        ClassEnrollment::query()->create($data);

        return redirect()->route('class-enrollments.index')->with('success', 'Member enrolled successfully.');
    }

    public function update(Request $request, ClassEnrollment $classEnrollment): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:enrolled,cancelled,attended'],
        ]);

        if ($data['status'] === 'enrolled' && $classEnrollment->status !== 'enrolled') {
            $schedule = ClassSchedule::query()
                ->with('gymClass')
                ->findOrFail($classEnrollment->class_schedule_id);
            $enrolledCount = ClassEnrollment::query()
                ->where('class_schedule_id', $schedule->id)
                ->where('status', 'enrolled')
                ->count();

            if ($enrolledCount >= $schedule->gymClass->capacity) {
                return back()->with('error', 'This class schedule is at capacity.');
            }
        }

        $classEnrollment->update($data);

        return redirect()->route('class-enrollments.index')->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(ClassEnrollment $classEnrollment): RedirectResponse
    {
        $classEnrollment->delete();

        return redirect()->route('class-enrollments.index')->with('success', 'Enrollment deleted successfully.');
    }
}
