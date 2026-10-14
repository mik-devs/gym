import { Head, Link, router, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';
import {
    create as roleCreate,
    destroy as roleDestroy,
    edit as roleEdit,
    index as roleIndex,
} from '@/routes/roles';

type RoleRow = {
    id: number;
    name: string;
    permissions_count: number;
    users_count: number;
};
type PageProps = {
    auth: { permissions: string[] };
    flash: { success?: string; error?: string };
};
type Props = {
    roles: {
        data: RoleRow[];
        links: { url: string | null; label: string; active: boolean }[];
    };
};

export default function Index({ roles }: Props) {
    const { auth, flash } = usePage<PageProps>().props;
    const can = (permission: string) => auth.permissions.includes(permission);
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Roles', href: roleIndex().url },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Roles" />
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
                <header className="flex items-center justify-between gap-4">
                    <h1 className="text-2xl font-semibold">Roles</h1>
                    {can('role.assign') && (
                        <Button asChild>
                            <Link href={roleCreate().url}>Create Role</Link>
                        </Button>
                    )}
                </header>
                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-muted/50">
                            <tr>
                                <th className="p-3">Role</th>
                                <th className="p-3">Permissions</th>
                                <th className="p-3">Users</th>
                                <th className="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {roles.data.map((role) => (
                                <tr key={role.id} className="border-t">
                                    <td className="p-3 font-medium">
                                        {role.name}
                                    </td>
                                    <td className="p-3">
                                        {role.permissions_count}
                                    </td>
                                    <td className="p-3">{role.users_count}</td>
                                    <td className="p-3">
                                        <div className="flex gap-2">
                                            <Button
                                                asChild
                                                size="sm"
                                                variant="outline"
                                            >
                                                <Link
                                                    href={roleEdit(role.id).url}
                                                >
                                                    Edit
                                                </Link>
                                            </Button>
                                            {role.name !== 'Admin' && (
                                                <Button
                                                    size="sm"
                                                    variant="destructive"
                                                    onClick={() => {
                                                        if (
                                                            confirm(
                                                                `Delete the ${role.name} role?`,
                                                            )
                                                        )
                                                            router.delete(
                                                                roleDestroy(
                                                                    role.id,
                                                                ).url,
                                                            );
                                                    }}
                                                >
                                                    Delete
                                                </Button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {roles.data.length === 0 && (
                                <tr>
                                    <td
                                        colSpan={4}
                                        className="p-6 text-center text-muted-foreground"
                                    >
                                        No roles found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Pagination" className="flex flex-wrap gap-2">
                    {roles.links.map((link, index) =>
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
