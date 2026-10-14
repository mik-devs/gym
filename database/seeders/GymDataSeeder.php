<?php

namespace Database\Seeders;

use App\Models\Attendance\Attendance;
use App\Models\Equipment\Equipment;
use App\Models\GymClass\ClassEnrollment;
use App\Models\GymClass\ClassSchedule;
use App\Models\GymClass\GymClass;
use App\Models\Member\Member;
use App\Models\Membership\Membership;
use App\Models\Membership\MembershipPlan;
use App\Models\Payment\Payment;
use App\Models\Setting\GymSetting;
use App\Models\Trainer\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GymDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = collect([
            ['name' => 'Monthly', 'duration_days' => 30, 'price' => 1000, 'description' => 'Basic monthly access'],
            ['name' => 'Quarterly', 'duration_days' => 90, 'price' => 2700, 'description' => '3-month plan'],
            ['name' => 'Annual', 'duration_days' => 365, 'price' => 10000, 'description' => 'Full year access'],
        ])->mapWithKeys(fn (array $plan): array => [
            $plan['name'] => MembershipPlan::withoutGlobalScope(SoftDeletingScope::class)->updateOrCreate(
                ['name' => $plan['name']],
                [
                    'duration_days' => $plan['duration_days'],
                    'price' => $plan['price'],
                    'description' => $plan['description'],
                    'features' => [],
                    'is_active' => true,
                ],
            ),
        ]);

        $memberDetails = [
            [
                'name' => 'Ava Santos',
                'phone' => '09171230001',
                'email' => 'ava.santos@example.com',
                'gender' => 'female',
                'dob' => '1995-04-12',
                'join_date' => Carbon::today()->subDays(30),
                'status' => 'active',
            ],
            [
                'name' => 'Liam Reyes',
                'phone' => '09171230002',
                'email' => 'liam.reyes@example.com',
                'gender' => 'male',
                'dob' => '1990-08-23',
                'join_date' => Carbon::today()->subDays(20),
                'status' => 'active',
            ],
            [
                'name' => 'Mia Cruz',
                'phone' => '09171230003',
                'email' => 'mia.cruz@example.com',
                'gender' => 'female',
                'dob' => '1988-01-17',
                'join_date' => Carbon::today()->subDays(10),
                'status' => 'active',
            ],
            [
                'name' => 'Noah Garcia',
                'phone' => '09171230004',
                'email' => 'noah.garcia@example.com',
                'gender' => 'male',
                'dob' => '1998-11-05',
                'join_date' => Carbon::today()->subDays(60),
                'status' => 'inactive',
            ],
            [
                'name' => 'Kai Dela Cruz',
                'phone' => '09171230005',
                'email' => 'kai.delacruz@example.com',
                'gender' => 'other',
                'dob' => '1992-06-30',
                'join_date' => Carbon::today()->subDays(365),
                'status' => 'expired',
            ],
        ];

        $members = collect($memberDetails)->mapWithKeys(fn (array $details): array => [
            $details['email'] => Member::query()->updateOrCreate(
                ['email' => $details['email']],
                [
                    'name' => $details['name'],
                    'phone' => $details['phone'],
                    'gender' => $details['gender'],
                    'dob' => $details['dob'],
                    'join_date' => $details['join_date'],
                    'status' => $details['status'],
                ],
            ),
        ]);

        $startDate = Carbon::today();
        $membershipDetails = [
            ['member' => 'ava.santos@example.com', 'plan' => 'Monthly'],
            ['member' => 'liam.reyes@example.com', 'plan' => 'Quarterly'],
            ['member' => 'mia.cruz@example.com', 'plan' => 'Annual'],
        ];

        $memberships = collect($membershipDetails)->mapWithKeys(function (array $details) use ($members, $plans, $startDate): array {
            $member = $members[$details['member']];
            $plan = $plans[$details['plan']];

            $membership = Membership::query()->updateOrCreate(
                ['member_id' => $member->id],
                [
                    'membership_plan_id' => $plan->id,
                    'start_date' => $startDate,
                    'end_date' => $startDate->copy()->addDays($plan->duration_days),
                    'status' => 'active',
                    'notes' => null,
                ],
            );

            return [$details['member'] => $membership];
        });

        foreach ([
            ['member' => 'ava.santos@example.com', 'amount' => 1000, 'method' => 'cash', 'status' => 'paid'],
            ['member' => 'liam.reyes@example.com', 'amount' => 2700, 'method' => 'gcash', 'status' => 'paid'],
            ['member' => 'mia.cruz@example.com', 'amount' => 10000, 'method' => 'card', 'status' => 'pending'],
        ] as $index => $paymentDetails) {
            $membership = $memberships[$paymentDetails['member']];

            Payment::query()->updateOrCreate(
                ['membership_id' => $membership->id],
                [
                    'invoice_no' => 'INV-'.now()->format('Ymd').'-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'amount' => $paymentDetails['amount'],
                    'method' => $paymentDetails['method'],
                    'status' => $paymentDetails['status'],
                    'paid_at' => $paymentDetails['status'] === 'paid' ? now() : null,
                    'notes' => 'Sample payment.',
                ],
            );
        }

        $attendanceDetails = [
            ['member' => 'ava.santos@example.com'],
            ['member' => 'liam.reyes@example.com'],
            ['member' => 'mia.cruz@example.com'],
            ['member' => 'ava.santos@example.com'],
            ['member' => 'liam.reyes@example.com'],
        ];

        foreach ($attendanceDetails as $index => $details) {
            $checkInAt = now();
            $member = $members[$details['member']];

            Attendance::query()->updateOrCreate(
                [
                    'member_id' => $member->id,
                    'notes' => 'GymDataSeeder attendance '.($index + 1),
                ],
                [
                    'check_in_at' => $checkInAt,
                    'check_out_at' => $checkInAt->copy()->addHour(),
                ],
            );
        }

        $trainerUser = User::withoutGlobalScope(SoftDeletingScope::class)
            ->where('email', 'trainer@example.com')
            ->firstOrFail();

        $trainers = collect([
            [
                'name' => 'Coach Mike',
                'phone' => '09172340001',
                'email' => 'coach.mike@example.com',
                'specialty' => 'Yoga, Pilates',
                'user_id' => $trainerUser->id,
            ],
            [
                'name' => 'Coach Sara',
                'phone' => '09172340002',
                'email' => 'coach.sara@example.com',
                'specialty' => 'HIIT, Strength',
                'user_id' => null,
            ],
        ])->mapWithKeys(fn (array $details): array => [
            $details['name'] => Trainer::query()->updateOrCreate(
                $details['user_id'] !== null
                    ? ['user_id' => $details['user_id']]
                    : ['email' => $details['email']],
                [
                    'user_id' => $details['user_id'],
                    'name' => $details['name'],
                    'phone' => $details['phone'],
                    'email' => $details['email'],
                    'specialty' => $details['specialty'],
                    'hire_date' => Carbon::today()->subYears(2),
                    'status' => 'active',
                ],
            ),
        ]);

        $classes = collect([
            [
                'name' => 'Morning Yoga',
                'trainer' => 'Coach Mike',
                'capacity' => 15,
                'duration_minutes' => 60,
                'location' => 'Studio A',
            ],
            [
                'name' => 'HIIT Blast',
                'trainer' => 'Coach Sara',
                'capacity' => 20,
                'duration_minutes' => 45,
                'location' => 'Main Floor',
            ],
        ])->mapWithKeys(fn (array $details): array => [
            $details['name'] => GymClass::query()->updateOrCreate(
                ['name' => $details['name']],
                [
                    'trainer_id' => $trainers[$details['trainer']]->id,
                    'capacity' => $details['capacity'],
                    'duration_minutes' => $details['duration_minutes'],
                    'location' => $details['location'],
                    'status' => 'active',
                    'description' => null,
                ],
            ),
        ]);

        $scheduleDetails = [
            ['class' => 'Morning Yoga', 'day' => 'monday', 'start' => '07:00', 'end' => '08:00'],
            ['class' => 'Morning Yoga', 'day' => 'wednesday', 'start' => '07:00', 'end' => '08:00'],
            ['class' => 'Morning Yoga', 'day' => 'friday', 'start' => '07:00', 'end' => '08:00'],
            ['class' => 'HIIT Blast', 'day' => 'tuesday', 'start' => '18:00', 'end' => '18:45'],
            ['class' => 'HIIT Blast', 'day' => 'thursday', 'start' => '18:00', 'end' => '18:45'],
        ];

        $schedules = collect($scheduleDetails)->map(fn (array $details): ClassSchedule => ClassSchedule::query()->updateOrCreate(
            [
                'class_id' => $classes[$details['class']]->id,
                'day_of_week' => $details['day'],
                'start_time' => $details['start'],
            ],
            ['end_time' => $details['end']],
        ));

        $firstSchedule = $schedules->first();

        foreach (['ava.santos@example.com', 'liam.reyes@example.com'] as $memberEmail) {
            ClassEnrollment::query()->updateOrCreate(
                [
                    'class_schedule_id' => $firstSchedule->id,
                    'member_id' => $members[$memberEmail]->id,
                ],
                [
                    'status' => 'enrolled',
                    'enrolled_at' => now(),
                ],
            );
        }

        foreach ([
            ['name' => 'Treadmill', 'quantity' => 5, 'condition' => 'good', 'status' => 'available'],
            ['name' => 'Dumbbell Set', 'quantity' => 10, 'condition' => 'good', 'status' => 'available'],
            ['name' => 'Rowing Machine', 'quantity' => 2, 'condition' => 'fair', 'status' => 'maintenance'],
        ] as $details) {
            Equipment::query()->updateOrCreate(
                ['name' => $details['name']],
                [
                    'quantity' => $details['quantity'],
                    'purchase_date' => Carbon::today()->subYear(),
                    'condition' => $details['condition'],
                    'status' => $details['status'],
                    'notes' => null,
                ],
            );
        }

        $gymSetting = GymSetting::query()->first() ?? new GymSetting;
        $gymSetting->fill([
            'gym_name' => 'PowerHouse Gym',
            'currency' => 'PHP',
            'tax_rate' => 12,
            'invoice_prefix' => 'INV-',
            'opening_time' => '06:00',
            'closing_time' => '22:00',
            'address' => '123 Main St',
            'phone' => '09171234567',
            'email' => 'info@powerhouse.test',
        ])->save();
    }
}
