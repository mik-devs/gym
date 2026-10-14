<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipPlanRequest;
use App\Http\Requests\UpdateMembershipPlanRequest;
use App\Models\Membership\MembershipPlan;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class MembershipPlanController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:plan.view', only: ['index', 'show']),
            new Middleware('permission:plan.create', only: ['create', 'store']),
            new Middleware('permission:plan.edit', only: ['edit', 'update']),
            new Middleware('permission:plan.delete', only: ['destroy']),
        ];
    }

    public function index(): Response
    {
        $plans = MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
            ->withCount('memberships')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MembershipPlans/Index', [
            'plans' => $plans,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('MembershipPlans/Create');
    }

    public function store(StoreMembershipPlanRequest $request): RedirectResponse
    {
        MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)->create($request->validated());

        return redirect()->route('plans.index')->with('success', 'Membership plan created successfully.');
    }

    public function show(string $membershipPlan): Response
    {
        $plan = $this->findPlanOrFail($membershipPlan);
        $plan->loadCount('memberships');

        return Inertia::render('MembershipPlans/Show', [
            'plan' => $plan,
        ]);
    }

    public function edit(string $membershipPlan): Response
    {
        return Inertia::render('MembershipPlans/Edit', [
            'plan' => $this->findPlanOrFail($membershipPlan),
        ]);
    }

    public function update(UpdateMembershipPlanRequest $request, string $membershipPlan): RedirectResponse
    {
        $this->findPlanOrFail($membershipPlan)->fill($request->validated())->save();

        return redirect()->route('plans.index')->with('success', 'Membership plan updated successfully.');
    }

    public function destroy(string $membershipPlan): RedirectResponse
    {
        $plan = $this->findPlanOrFail($membershipPlan);

        if ($plan->memberships()->where('status', 'active')->exists()) {
            return back()->with('error', 'A plan with active memberships cannot be deleted.');
        }

        // This table has no deleted_at column, so a soft delete cannot be persisted.
        $plan->forceDelete();

        return redirect()->route('plans.index')->with('success', 'Membership plan deleted successfully.');
    }

    private function findPlanOrFail(string $id): MembershipPlan
    {
        return MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)->findOrFail($id);
    }
}
