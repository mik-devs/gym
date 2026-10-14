<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGymSettingRequest;
use App\Models\Setting\GymSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class GymSettingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:setting.manage'),
        ];
    }

    public function index(): Response
    {
        return Inertia::render('Settings/Gym', [
            'setting' => GymSetting::current(),
        ]);
    }

    public function edit(): Response
    {
        return Inertia::render('Settings/Gym', [
            'setting' => GymSetting::current(),
        ]);
    }

    public function update(UpdateGymSettingRequest $request): RedirectResponse
    {
        GymSetting::current()->update($request->validated());

        return redirect()->route('settings.index')->with('success', 'Gym settings updated successfully.');
    }
}
