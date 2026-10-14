<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    private const PERMISSIONS = [
        'user.view',
        'user.create',
        'user.edit',
        'user.delete',
        'role.assign',
        'member.view',
        'member.create',
        'member.edit',
        'member.delete',
        'payment.view',
        'payment.create',
        'payment.edit',
        'payment.delete',
        'attendance.view',
        'attendance.create',
        'attendance.edit',
        'class.view',
        'class.create',
        'class.edit',
        'class.delete',
        'trainer.view',
        'trainer.create',
        'trainer.edit',
        'trainer.delete',
        'equipment.view',
        'equipment.create',
        'equipment.edit',
        'equipment.delete',
        'membership.view',
        'membership.create',
        'membership.edit',
        'membership.delete',
        'plan.view',
        'plan.create',
        'plan.edit',
        'plan.delete',
        'report.view',
        'setting.manage',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->whereIn('name', self::PERMISSIONS)
            ->get();

        $adminRole = Role::query()->firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);
        $staffRole = Role::query()->firstOrCreate([
            'name' => 'Staff',
            'guard_name' => 'web',
        ]);
        $trainerRole = Role::query()->firstOrCreate([
            'name' => 'Trainer',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions($permissions);
        $staffRole->syncPermissions($permissions->filter(
            fn (Permission $permission): bool => in_array($permission->name, [
                'member.view',
                'member.create',
                'member.edit',
                'member.delete',
                'payment.view',
                'payment.create',
                'payment.edit',
                'payment.delete',
                'attendance.view',
                'attendance.create',
                'attendance.edit',
                'membership.view',
                'membership.create',
                'membership.edit',
                'membership.delete',
                'plan.view',
                'class.view',
                'report.view',
            ], true),
        ));
        $trainerRole->syncPermissions($permissions->filter(
            fn (Permission $permission): bool => in_array($permission->name, [
                'member.view',
                'class.view',
                'class.edit',
                'attendance.view',
                'attendance.create',
            ], true),
        ));

        foreach ([
            ['name' => 'System Admin', 'email' => 'admin@example.com', 'role' => 'Admin'],
            ['name' => 'Front Desk', 'email' => 'staff@example.com', 'role' => 'Staff'],
            ['name' => 'Coach Mike', 'email' => 'trainer@example.com', 'role' => 'Trainer'],
        ] as $account) {
            $user = User::withoutGlobalScope(SoftDeletingScope::class)->firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => 'password',
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$account['role']]);
        }
    }
}
