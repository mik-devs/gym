<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipRequest;
use App\Http\Requests\UpdateMembershipRequest;
use App\Models\Member\Member;
use App\Models\Membership\Membership;
use App\Models\Membership\MembershipPlan;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class MembershipController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:membership.view', only: ['index', 'show']),
            new Middleware('permission:membership.create', only: ['create', 'store']),
            new Middleware('permission:membership.edit', only: ['edit', 'update']),
            new Middleware('permission:membership.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $query = Membership::query()->with(['member', 'membershipPlan']);
        $status = $request->string('status')->toString();

        if ($status !== '') {
            $query->where('status', $status);
        }

        return Inertia::render('Memberships/Index', [
            'memberships' => $query->latest('id')->paginate(10)->withQueryString(),
            'filters' => ['status' => $status],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Memberships/Create', [
            'members' => Member::query()->orderBy('name')->get(['id', 'name']),
            'plans' => MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'duration_days', 'price']),
        ]);
    }

    public function store(StoreMembershipRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $plan = MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
            ->findOrFail((int) $data['membership_plan_id']);
        $data['end_date'] ??= Carbon::parse($data['start_date'])->addDays($plan->duration_days);

        Membership::query()->create($data);

        return redirect()->route('memberships.index')->with('success', 'Membership created successfully.');
    }

    public function show(Membership $membership): Response
    {
        $membership->load(['member', 'membershipPlan', 'payments']);

        return Inertia::render('Memberships/Show', [
            'membership' => $membership,
        ]);
    }

    public function edit(Membership $membership): Response
    {
        return Inertia::render('Memberships/Edit', [
            'membership' => $membership,
            'members' => Member::query()->orderBy('name')->get(['id', 'name']),
            'plans' => MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
                ->orderBy('name')
                ->get(['id', 'name', 'duration_days', 'price']),
        ]);
    }

    public function update(UpdateMembershipRequest $request, Membership $membership): RedirectResponse
    {
        $data = $request->validated();

        if (! array_key_exists('end_date', $data) || $data['end_date'] === null) {
            $plan = MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
                ->findOrFail((int) $data['membership_plan_id']);
            $data['end_date'] = Carbon::parse($data['start_date'])->addDays($plan->duration_days);
        }

        $membership->update($data);

        return redirect()->route('memberships.index')->with('success', 'Membership updated successfully.');
    }

    public function destroy(Membership $membership): RedirectResponse
    {
        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'Membership deleted successfully.');
    }
}
