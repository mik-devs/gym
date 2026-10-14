import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';
import {
    edit as roleEdit,
    index as roleIndex,
    update as roleUpdate,
} from '@/routes/roles';

type RoleData = { id: number; name: string; permissions: string[] };

export default function Edit({
    role,
    permissions,
}: {
    role: RoleData;
    permissions: string[];
}) {
    const form = useForm<{ name: string; permissions: string[] }>({
        name: role.name,
        permissions: role.permissions,
    });
    const groups = permissions.reduce<Record<string, string[]>>(
        (result, permission) => {
            const prefix = permission.split('.')[0];
            (result[prefix] ??= []).push(permission);
            return result;
        },
        {},
    );
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Roles', href: roleIndex().url },
        { title: 'Edit', href: roleEdit(role.id).url },
    ];

    function togglePermission(permission: string) {
        form.setData(
            'permissions',
            form.data.permissions.includes(permission)
                ? form.data.permissions.filter((item) => item !== permission)
                : [...form.data.permissions, permission],
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Role" />
            <main className="mx-auto w-full max-w-3xl space-y-6 p-4 md:p-6">
                <h1 className="text-2xl font-semibold">Edit Role</h1>
                <form
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.put(roleUpdate(role.id).url);
                    }}
                    className="space-y-6"
                >
                    <div className="grid gap-2">
                        <Label htmlFor="name">Role Name</Label>
                        <Input
                            id="name"
                            value={form.data.name}
                            disabled={role.name === 'Admin'}
                            onChange={(event) =>
                                form.setData('name', event.target.value)
                            }
                        />
                        {role.name === 'Admin' && (
                            <p className="text-sm text-muted-foreground">
                                Admin role cannot be renamed.
                            </p>
                        )}
                        <InputError message={form.errors.name} />
                    </div>
                    <fieldset className="space-y-4">
                        <legend className="font-medium">Permissions</legend>
                        <div className="grid gap-4 sm:grid-cols-2">
                            {Object.entries(groups).map(([group, items]) => (
                                <section
                                    key={group}
                                    className="grid content-start gap-3 rounded-lg border p-4"
                                >
                                    <h2 className="font-medium capitalize">
                                        {group}
                                    </h2>
                                    {items.map((permission) => (
                                        <label
                                            key={permission}
                                            className="flex items-center gap-2 text-sm"
                                        >
                                            <input
                                                type="checkbox"
                                                checked={form.data.permissions.includes(
                                                    permission,
                                                )}
                                                onChange={() =>
                                                    togglePermission(permission)
                                                }
                                                className="size-4 rounded border-input"
                                            />
                                            {permission}
                                        </label>
                                    ))}
                                </section>
                            ))}
                        </div>
                    </fieldset>
                    <InputError message={form.errors.permissions} />
                    <div className="flex gap-2">
                        <Button disabled={form.processing}>Save Changes</Button>
                        <Button asChild variant="outline">
                            <Link href={roleIndex().url}>Cancel</Link>
                        </Button>
                    </div>
                </form>
            </main>
        </AppLayout>
    );
}
