<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member\Member;
use App\Models\Membership\Membership;
use App\Models\Membership\MembershipPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:member.view', only: ['index', 'show']),
            new Middleware('permission:member.create', only: ['create', 'store']),
            new Middleware('permission:member.edit', only: ['edit', 'update']),
            new Middleware('permission:member.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $query = Member::query()->with([
            'memberships' => fn (HasMany $query) => $query
                ->where('status', 'active')
                ->with('membershipPlan')
                ->latest('start_date'),
        ]);

        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $members = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Member $member): array => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'email' => $member->email,
                'status' => $member->status,
                'join_date' => $member->join_date,
                'active_plan' => $member->memberships->first()?->membershipPlan?->name,
            ]);

        return Inertia::render('Members/Index', [
            'members' => $members,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Members/Create', [
            'plans' => MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'duration_days', 'price']),
        ]);
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $planId = $data['plan_id'] ?? null;
        unset($data['plan_id']);

        $member = Member::query()->create($data);

        if ($planId !== null) {
            $plan = MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)->findOrFail((int) $planId);
            $startDate = Carbon::today();

            Membership::query()->create([
                'member_id' => $member->id,
                'membership_plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays($plan->duration_days),
                'status' => 'active',
            ]);
        }

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function show(Member $member): Response
    {
        $member->load([
            'memberships.membershipPlan',
            'memberships.payments',
            'attendances' => fn (HasMany $query) => $query->latest('check_in_at'),
        ]);

        return Inertia::render('Members/Show', [
            'member' => $member,
        ]);
    }

    public function edit(Member $member): Response
    {
        return Inertia::render('Members/Edit', [
            'member' => $member,
            'plans' => MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'duration_days', 'price']),
        ]);
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}
