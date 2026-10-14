import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { home } from '@/routes';

type AuthSimpleLayoutProps = {
    children: ReactNode;
    title?: string;
    description?: string;
};

function BrandMark({ compact = false }: { compact?: boolean }) {
    return (
        <Link
            href={home()}
            className={`inline-flex items-center gap-3 font-black tracking-[0.18em] text-[#F5F5F0] ${compact ? 'text-sm' : 'text-base'}`}
        >
            <span className="flex size-10 items-center justify-center border border-[#F97316] text-sm font-black tracking-normal text-[#F97316]">
                IG
            </span>
            <span>IRON GYM</span>
        </Link>
    );
}

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthSimpleLayoutProps) {
    return (
        <main className="dark min-h-svh bg-[#0B0B0B] text-[#F5F5F0]">
            <div className="grid min-h-svh lg:grid-cols-2">
                <section className="relative hidden min-h-svh overflow-hidden border-r border-[#262626] bg-[#0B0B0B] p-10 lg:flex lg:flex-col lg:justify-between xl:p-14">
                    <div className="pointer-events-none absolute inset-0 opacity-[0.06]">
                        <svg
                            aria-hidden="true"
                            viewBox="0 0 640 480"
                            fill="none"
                            className="absolute -right-24 bottom-0 h-[min(80vh,640px)] w-[min(80vw,800px)] rotate-[-18deg] text-[#F97316]"
                        >
                            <g
                                stroke="currentColor"
                                strokeWidth="3"
                                vectorEffect="non-scaling-stroke"
                            >
                                <path d="M112 240h416M160 168v144m-28-116v88m376-116v144m28-116v88M160 240h320" />
                                <circle cx="320" cy="240" r="154" />
                                <circle cx="320" cy="240" r="112" />
                            </g>
                        </svg>
                    </div>
                    <div className="absolute inset-0 bg-gradient-to-br from-black/80 via-black/60 to-black/40" />
                    <div className="relative z-10">
                        <BrandMark />
                    </div>
                    <div className="relative z-10 max-w-xl space-y-5">
                        <p className="text-xs font-bold tracking-[0.2em] text-[#F97316] uppercase">
                            Strength · Conditioning · Community
                        </p>
                        <h2 className="text-5xl leading-[0.95] font-black tracking-tight text-[#F5F5F0] uppercase xl:text-7xl">
                            Train hard.
                            <br />
                            Stay strong.
                        </h2>
                        <p className="text-sm tracking-wide text-[#A3A3A3]">
                            Est. 2026 — Strength, Conditioning, Community
                        </p>
                    </div>
                </section>

                <section className="flex min-h-svh flex-col bg-[#0B0B0B] px-6 py-8 sm:px-10 lg:justify-center lg:px-12 xl:px-20">
                    <div className="mx-auto flex w-full max-w-[420px] flex-1 flex-col lg:flex-initial">
                        <div className="mb-10 lg:hidden">
                            <BrandMark compact />
                        </div>
                        {(title || description) && (
                            <header className="mb-8 space-y-2">
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
                    </div>
                </section>
            </div>
        </main>
    );
}
