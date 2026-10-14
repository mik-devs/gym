<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Models\Trainer\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class TrainerController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:trainer.view', only: ['index', 'show']),
            new Middleware('permission:trainer.create', only: ['create', 'store']),
            new Middleware('permission:trainer.edit', only: ['edit', 'update']),
            new Middleware('permission:trainer.delete', only: ['destroy']),
        ];
    }

    public function index(): Response
    {
        $trainers = Trainer::query()
            ->withCount('classes')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Trainers/Index', [
            'trainers' => $trainers,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Trainers/Create', [
            'users' => User::withoutGlobalScope(SoftDeletingScope::class)
                ->whereDoesntHave('trainer')
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    public function store(StoreTrainerRequest $request): RedirectResponse
    {
        Trainer::query()->create($request->validated());

        return redirect()->route('trainers.index')->with('success', 'Trainer created successfully.');
    }

    public function show(Trainer $trainer): Response
    {
        return Inertia::render('Trainers/Show', [
            'trainer' => $trainer->load('classes'),
        ]);
    }

    public function edit(Trainer $trainer): Response
    {
        return Inertia::render('Trainers/Edit', [
            'trainer' => $trainer,
            'users' => User::withoutGlobalScope(SoftDeletingScope::class)
                ->where(function ($query) use ($trainer): void {
                    $query->whereDoesntHave('trainer')
                        ->orWhereKey($trainer->user_id);
                })
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    public function update(UpdateTrainerRequest $request, Trainer $trainer): RedirectResponse
    {
        $trainer->update($request->validated());

        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully.');
    }

    public function destroy(Trainer $trainer): RedirectResponse
    {
        $trainer->delete();

        return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully.');
    }
}
