import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';
import {
    create as userCreate,
    destroy as userDestroy,
    edit as userEdit,
    index as userIndex,
    show as userShow,
} from '@/routes/users';

type UserRow = {
    id: number;
    name: string;
    email: string;
    roles: string[];
    created_at: string;
};
type PageProps = {
    auth: { user: { id: number; name: string }; permissions: string[] };
    flash: { success?: string; error?: string };
};
type Props = {
    users: {
        data: UserRow[];
        links: { url: string | null; label: string; active: boolean }[];
        current_page: number;
        last_page: number;
        total: number;
    };
    filters: { search?: string };
};

export default function Index({ users, filters }: Props) {
    const { auth, flash } = usePage<PageProps>().props;
    const [search, setSearch] = useState(filters.search ?? '');
    const can = (permission: string) => auth.permissions.includes(permission);
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Users', href: userIndex().url },
    ];

    function submitSearch(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        router.get(
            userIndex().url,
            { search },
            { preserveState: true, replace: true },
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users" />
            <main className="space-y-6 p-4 md:p-6">
                {flash.success && (
                    <div className="rounded-md bg-green-100 p-3 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                        {flash.success}
                    </div>
                )}
                {flash.error && (
                    <div className="rounded-md bg-red-100 p-3 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                        {flash.error}
                    </div>
                )}
                <header className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold">Users</h1>
                        <p className="text-sm text-muted-foreground">
                            {users.total} total
                        </p>
                    </div>
                    {can('user.create') && (
                        <Link
                            className="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground hover:bg-primary/90"
                            href={userCreate().url}
                        >
                            Create User
                        </Link>
                    )}
                </header>
                <form onSubmit={submitSearch} className="flex max-w-lg gap-2">
                    <Input
                        aria-label="Search users"
                        placeholder="Search name or email"
                        value={search}
                        onChange={(event) => setSearch(event.target.value)}
                    />
                    <Button type="submit" variant="outline">
                        Search
                    </Button>
                </form>
                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-muted/50">
                            <tr>
                                <th className="p-3">Name</th>
                                <th className="p-3">Email</th>
                                <th className="p-3">Roles</th>
                                <th className="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {users.data.map((user) => (
                                <tr key={user.id} className="border-t">
                                    <td className="p-3 font-medium">
                                        {user.name}
                                    </td>
                                    <td className="p-3">{user.email}</td>
                                    <td className="p-3">
                                        <div className="flex flex-wrap gap-1">
                                            {user.roles.map((role) => (
                                                <Badge
                                                    key={role}
                                                    variant="secondary"
                                                >
                                                    {role}
                                                </Badge>
                                            ))}
                                        </div>
                                    </td>
                                    <td className="p-3">
                                        <div className="flex gap-2">
                                            {can('user.view') && (
                                                <Link
                                                    className="rounded-md border px-3 py-1 text-sm hover:bg-muted"
                                                    href={userShow(user.id).url}
                                                >
                                                    View
                                                </Link>
                                            )}
                                            {can('user.edit') && (
                                                <Link
                                                    className="rounded-md border px-3 py-1 text-sm hover:bg-muted"
                                                    href={userEdit(user.id).url}
                                                >
                                                    Edit
                                                </Link>
                                            )}
                                            {can('user.delete') && (
                                                <button
                                                    className="rounded-md bg-destructive px-3 py-1 text-sm text-white hover:bg-destructive/90"
                                                    onClick={() => {
                                                        if (
                                                            confirm(
                                                                `Delete ${user.name}?`,
                                                            )
                                                        )
                                                            router.delete(
                                                                userDestroy(
                                                                    user.id,
                                                                ).url,
                                                            );
                                                    }}
                                                >
                                                    Delete
                                                </button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Pagination" className="flex flex-wrap gap-2">
                    {users.links.map((link, index) =>
                        link.url ? (
                            <Link
                                key={`${link.label}-${index}`}
                                href={link.url}
                                preserveScroll
                                className={`rounded border px-3 py-1 text-sm ${link.active ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span
                                key={`${link.label}-${index}`}
                                className="rounded border px-3 py-1 text-sm text-muted-foreground"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ),
                    )}
                </nav>
            </main>
        </AppLayout>
    );
}
