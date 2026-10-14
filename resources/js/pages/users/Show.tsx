import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';
import {
    edit as userEdit,
    index as userIndex,
    show as userShow,
} from '@/routes/users';

type UserData = {
    id: number;
    name: string;
    email: string;
    roles: string[];
    permissions: string[];
    created_at: string;
};
type PageProps = { auth: { permissions: string[] } };

export default function Show({ user }: { user: UserData }) {
    const { auth } = usePage<PageProps>().props;
    const canEdit = auth.permissions.includes('user.edit');
    const grouped = user.permissions.reduce<Record<string, string[]>>(
        (groups, permission) => {
            const prefix = permission.split('.')[0];
            (groups[prefix] ??= []).push(permission);
            return groups;
        },
        {},
    );
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Users', href: userIndex().url },
        { title: user.name, href: userShow(user.id).url },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={user.name} />
            <main className="space-y-6 p-4 md:p-6">
                <header className="flex flex-wrap items-center justify-between gap-4">
                    <h1 className="text-2xl font-semibold">{user.name}</h1>
                    {canEdit && (
                        <Button asChild>
                            <Link href={userEdit(user.id).url}>Edit User</Link>
                        </Button>
                    )}
                </header>
                <section className="grid gap-4 rounded-lg border p-5 sm:grid-cols-2">
                    <div>
                        <h2 className="text-sm text-muted-foreground">Name</h2>
                        <p className="font-medium">{user.name}</p>
                    </div>
                    <div>
                        <h2 className="text-sm text-muted-foreground">Email</h2>
                        <p className="font-medium">{user.email}</p>
                    </div>
                    <div className="sm:col-span-2">
                        <h2 className="mb-2 text-sm text-muted-foreground">
                            Roles
                        </h2>
                        <div className="flex flex-wrap gap-2">
                            {user.roles.map((role) => (
                                <Badge key={role} variant="secondary">
                                    {role}
                                </Badge>
                            ))}
                        </div>
                    </div>
                </section>
                <section className="space-y-4">
                    <h2 className="text-lg font-semibold">Permissions</h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {Object.entries(grouped).map(([group, permissions]) => (
                            <div
                                key={group}
                                className="space-y-2 rounded-lg border p-4"
                            >
                                <h3 className="font-medium capitalize">
                                    {group}
                                </h3>
                                <div className="flex flex-wrap gap-2">
                                    {permissions.map((permission) => (
                                        <Badge
                                            key={permission}
                                            variant="outline"
                                        >
                                            {permission}
                                        </Badge>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </section>
                <Button asChild variant="outline">
                    <Link href={userIndex().url}>Back to Users</Link>
                </Button>
            </main>
        </AppLayout>
    );
}
