import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';
import {
    create as userCreate,
    index as userIndex,
    store as userStore,
} from '@/routes/users';

type FormData = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    roles: string[];
};

export default function Create({ roles }: { roles: string[] }) {
    const form = useForm<FormData>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        roles: [],
    });
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Users', href: userIndex().url },
        { title: 'Create', href: userCreate().url },
    ];

    function toggleRole(role: string) {
        form.setData(
            'roles',
            form.data.roles.includes(role)
                ? form.data.roles.filter((item) => item !== role)
                : [...form.data.roles, role],
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create User" />
            <main className="mx-auto w-full max-w-2xl space-y-6 p-4 md:p-6">
                <h1 className="text-2xl font-semibold">Create User</h1>
                <form
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.post(userStore().url);
                    }}
                    className="space-y-5"
                >
                    {(
                        [
                            'name',
                            'email',
                            'password',
                            'password_confirmation',
                        ] as const
                    ).map((field) => (
                        <div key={field} className="grid gap-2">
                            <Label htmlFor={field}>
                                {field === 'password_confirmation'
                                    ? 'Confirm Password'
                                    : field[0].toUpperCase() + field.slice(1)}
                            </Label>
                            <Input
                                id={field}
                                type={
                                    field.includes('password')
                                        ? 'password'
                                        : field
                                }
                                autoComplete={
                                    field === 'name'
                                        ? 'name'
                                        : field === 'email'
                                          ? 'email'
                                          : undefined
                                }
                                value={form.data[field]}
                                onChange={(event) =>
                                    form.setData(field, event.target.value)
                                }
                            />
                            <InputError message={form.errors[field]} />
                        </div>
                    ))}
                    <fieldset className="grid gap-3">
                        <legend className="text-sm font-medium">Roles</legend>
                        {roles.map((role) => (
                            <label
                                key={role}
                                className="flex items-center gap-2 text-sm"
                            >
                                <input
                                    type="checkbox"
                                    checked={form.data.roles.includes(role)}
                                    onChange={() => toggleRole(role)}
                                    className="size-4 rounded border-input"
                                />
                                {role}
                            </label>
                        ))}
                    </fieldset>
                    <InputError message={form.errors.roles} />
                    <div className="flex gap-2">
                        <Button disabled={form.processing}>Create User</Button>
                        <Button asChild variant="outline">
                            <Link href={userIndex().url}>Cancel</Link>
                        </Button>
                    </div>
                </form>
            </main>
        </AppLayout>
    );
}
