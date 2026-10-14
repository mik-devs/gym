<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Membership\Membership;
use App\Models\Payment\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:payment.view', only: ['index', 'show']),
            new Middleware('permission:payment.create', only: ['create', 'store']),
            new Middleware('permission:payment.edit', only: ['edit', 'update']),
            new Middleware('permission:payment.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): Response
    {
        $query = Payment::query()->with(['membership.member', 'membership.membershipPlan']);
        $status = $request->string('status')->toString();
        $method = $request->string('method')->toString();
        $search = $request->string('search')->toString();

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($method !== '') {
            $query->where('method', $method);
        }

        if ($search !== '') {
            $query->where('invoice_no', 'like', "%{$search}%");
        }

        return Inertia::render('Payments/Index', [
            'payments' => $query->latest('id')->paginate(10)->withQueryString(),
            'filters' => [
                'status' => $status,
                'method' => $method,
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Payments/Create', [
            'memberships' => Membership::query()
                ->where('status', 'active')
                ->with(['member', 'membershipPlan'])
                ->latest('start_date')
                ->get(),
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['paid_at'] = $data['status'] === 'paid'
            ? ($data['paid_at'] ?? now())
            : null;

        Payment::query()->create($data);

        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function show(Payment $payment): Response
    {
        $payment->load(['membership.member', 'membership.membershipPlan']);

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function edit(Payment $payment): Response
    {
        return Inertia::render('Payments/Edit', [
            'payment' => $payment->load(['membership.member', 'membership.membershipPlan']),
            'memberships' => Membership::query()
                ->with(['member', 'membershipPlan'])
                ->latest('start_date')
                ->get(),
        ]);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        $data = $request->validated();
        $data['paid_at'] = $data['status'] === 'paid'
            ? ($data['paid_at'] ?? now())
            : null;

        $payment->update($data);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
