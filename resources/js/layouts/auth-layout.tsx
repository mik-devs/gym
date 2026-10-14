import { usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';
import AuthCardLayout from '@/layouts/auth/auth-card-layout';
import AuthLayoutTemplate from '@/layouts/auth/auth-simple-layout';

export default function AuthLayout({
    title = '',
    description = '',
    children,
}: {
    title?: string;
    description?: string;
    children: ReactNode;
}) {
    const { component } = usePage();
    const LayoutTemplate =
        component === 'auth/forgot-password' ||
        component === 'auth/reset-password'
            ? AuthCardLayout
            : AuthLayoutTemplate;

    return (
        <LayoutTemplate title={title} description={description}>
            {children}
        </LayoutTemplate>
    );
}
