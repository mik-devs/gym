import { Link } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';
import { home } from '@/routes';

export default function AuthCardLayout({
    children,
    title,
    description,
}: PropsWithChildren<{
    name?: string;
    title?: string;
    description?: string;
}>) {
    return (
        <main className="dark flex min-h-svh items-center justify-center bg-[#0B0B0B] px-6 py-10 text-[#F5F5F0]">
            <div className="w-full max-w-md">
                <Link
                    href={home()}
                    className="mb-8 flex items-center justify-center gap-3 font-black tracking-[0.18em] text-[#F5F5F0]"
                >
                    <span className="flex size-10 items-center justify-center border border-[#F97316] text-sm tracking-normal text-[#F97316]">
                        IG
                    </span>
                    <span>IRON GYM</span>
                </Link>

                <section className="border border-[#262626] bg-[#141414] px-6 py-8 sm:px-10 sm:py-10">
                    {(title || description) && (
                        <header className="mb-7 space-y-2">
                            {title && (
                                <h1 className="text-3xl font-black tracking-tight text-[#F5F5F0] uppercase">
                                    {title}
                                </h1>
                            )}
                            {description && (
                                <p className="text-sm leading-6 text-[#A3A3A3]">
                                    {description}
                                </p>
                            )}
                        </header>
                    )}
                    {children}
                </section>
            </div>
        </main>
    );
}
