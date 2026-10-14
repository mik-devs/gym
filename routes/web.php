<?php

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\GymClass\ClassEnrollmentController;
use App\Http\Controllers\GymClass\ClassScheduleController;
use App\Http\Controllers\GymClass\GymClassController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Membership\MembershipController;
use App\Http\Controllers\Membership\MembershipPlanController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Setting\GymSettingController;
use App\Http\Controllers\Trainer\TrainerController;
use App\Http\Controllers\UserController;
use App\Models\GymClass\GymClass;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

require __DIR__.'/settings.php';

Route::model('class', GymClass::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('permission:user.create')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware('permission:user.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    Route::middleware('permission:user.edit')->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user.delete')
        ->name('users.destroy');

    Route::middleware('permission:role.view')->group(function () {
        Route::resource('roles', RoleController::class)->only(['index']);
    });

    Route::middleware('permission:role.create')->group(function () {
        Route::resource('roles', RoleController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:role.edit')->group(function () {
        Route::resource('roles', RoleController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:role.delete')->group(function () {
        Route::resource('roles', RoleController::class)->only(['destroy']);
    });

    Route::middleware('permission:role.assign')->group(function () {
        Route::resource('permissions', PermissionController::class)->only(['index', 'destroy']);
    });

    Route::middleware('permission:member.view')->group(function () {
        Route::resource('members', MemberController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:member.create')->group(function () {
        Route::resource('members', MemberController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:member.edit')->group(function () {
        Route::resource('members', MemberController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:member.delete')->group(function () {
        Route::resource('members', MemberController::class)->only(['destroy']);
    });

    Route::middleware('permission:plan.view')->group(function () {
        Route::resource('plans', MembershipPlanController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:plan.create')->group(function () {
        Route::resource('plans', MembershipPlanController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:plan.edit')->group(function () {
        Route::resource('plans', MembershipPlanController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:plan.delete')->group(function () {
        Route::resource('plans', MembershipPlanController::class)->only(['destroy']);
    });

    Route::middleware('permission:membership.view')->group(function () {
        Route::resource('memberships', MembershipController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:membership.create')->group(function () {
        Route::resource('memberships', MembershipController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:membership.edit')->group(function () {
        Route::resource('memberships', MembershipController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:membership.delete')->group(function () {
        Route::resource('memberships', MembershipController::class)->only(['destroy']);
    });

    Route::middleware('permission:payment.view')->group(function () {
        Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:payment.create')->group(function () {
        Route::resource('payments', PaymentController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:payment.edit')->group(function () {
        Route::resource('payments', PaymentController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:payment.delete')->group(function () {
        Route::resource('payments', PaymentController::class)->only(['destroy']);
    });

    Route::middleware('permission:attendance.view')->group(function () {
        Route::resource('attendances', AttendanceController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:attendance.create')->group(function () {
        Route::resource('attendances', AttendanceController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:attendance.edit')->group(function () {
        Route::resource('attendances', AttendanceController::class)->only(['edit', 'update']);

        Route::patch('attendances/{attendance}/checkout', [AttendanceController::class, 'checkOut'])
            ->name('attendances.checkout');
    });

    Route::middleware('permission:attendance.delete')->group(function () {
        Route::resource('attendances', AttendanceController::class)->only(['destroy']);
    });

    Route::middleware('permission:trainer.view')->group(function () {
        Route::resource('trainers', TrainerController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:trainer.create')->group(function () {
        Route::resource('trainers', TrainerController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:trainer.edit')->group(function () {
        Route::resource('trainers', TrainerController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:trainer.delete')->group(function () {
        Route::resource('trainers', TrainerController::class)->only(['destroy']);
    });

    Route::middleware('permission:class.view')->group(function () {
        Route::resource('classes', GymClassController::class)
            ->parameters(['classes' => 'class'])
            ->only(['index', 'show']);
        Route::resource('class-schedules', ClassScheduleController::class)->only(['index']);
        Route::resource('class-enrollments', ClassEnrollmentController::class)->only(['index']);
    });

    Route::middleware('permission:class.create')->group(function () {
        Route::resource('classes', GymClassController::class)
            ->parameters(['classes' => 'class'])
            ->only(['create', 'store']);
        Route::resource('class-schedules', ClassScheduleController::class)->only(['create', 'store']);
        Route::resource('class-enrollments', ClassEnrollmentController::class)->only(['store']);
    });

    Route::middleware('permission:class.edit')->group(function () {
        Route::resource('classes', GymClassController::class)
            ->parameters(['classes' => 'class'])
            ->only(['edit', 'update']);
        Route::resource('class-schedules', ClassScheduleController::class)->only(['edit', 'update']);
        Route::resource('class-enrollments', ClassEnrollmentController::class)->only(['update']);
    });

    Route::middleware('permission:class.delete')->group(function () {
        Route::resource('classes', GymClassController::class)
            ->parameters(['classes' => 'class'])
            ->only(['destroy']);
        Route::resource('class-schedules', ClassScheduleController::class)->only(['destroy']);
        Route::resource('class-enrollments', ClassEnrollmentController::class)->only(['destroy']);
    });

    Route::middleware('permission:equipment.view')->group(function () {
        Route::resource('equipment', EquipmentController::class)->only(['index', 'show']);
    });

    Route::middleware('permission:equipment.create')->group(function () {
        Route::resource('equipment', EquipmentController::class)->only(['create', 'store']);
    });

    Route::middleware('permission:equipment.edit')->group(function () {
        Route::resource('equipment', EquipmentController::class)->only(['edit', 'update']);
    });

    Route::middleware('permission:equipment.delete')->group(function () {
        Route::resource('equipment', EquipmentController::class)->only(['destroy']);
    });

    Route::middleware('permission:setting.manage')->group(function () {
        Route::get('settings', [GymSettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [GymSettingController::class, 'update'])->name('settings.update');
    });
});
