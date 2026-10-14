import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

export default function Dashboard() {
    // TODO: replace with props from controller
    const stats = [
        {
            label: 'Active Members',
            value: '1,284',
            detail: '▲ 12% from last month',
            detailClass: 'text-xs font-medium text-emerald-400',
        },
        {
            label: "Today's Check-ins",
            value: '86',
            detail: 'as of 14:35',
            detailClass: 'text-xs font-medium text-[#A3A3A3]',
        },
        {
            label: 'Monthly Revenue',
            value: '₱248,500',
            detail: '▲ 8.4% from last month',
            detailClass: 'text-xs font-medium text-emerald-400',
        },
        {
            label: 'Expiring Soon',
            value: '23',
            detail: 'next 7 days',
            detailClass: 'text-xs font-medium text-[#A3A3A3]',
        },
    ];

    // TODO: replace with props from controller
    const recentCheckIns = [
        { name: 'Alex Morgan', time: '14:32', plan: 'Premium' },
        { name: 'Jordan Lee', time: '14:18', plan: 'Monthly' },
        { name: 'Taylor Kim', time: '13:56', plan: 'Annual' },
        { name: 'Casey Santos', time: '13:41', plan: 'Premium' },
        { name: 'Riley Cruz', time: '13:25', plan: 'Monthly' },
    ];

    // TODO: replace with props from controller
    const expiringMemberships = [
        { name: 'Sam Rivera', plan: 'Premium', expiry: 'In 2 days' },
        { name: 'Jamie Park', plan: 'Monthly', expiry: 'In 3 days' },
        { name: 'Drew Bennett', plan: 'Annual', expiry: 'In 5 days' },
        { name: 'Morgan Reyes', plan: 'Monthly', expiry: 'In 7 days' },
    ];

    return (
        <AppLayout breadcrumbs={[]}>
            <Head title="Dashboard" />
            <main className="min-h-full space-y-8 bg-[#0B0B0B] p-4 text-[#F5F5F0] md:p-6 lg:p-8">
                <header>
                    <h1 className="text-4xl leading-none font-black tracking-tight uppercase md:text-5xl">
                        COMMAND CENTER
                    </h1>
                </header>

                <div
                    aria-label="Gym statistics"
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    {stats.map((stat) => (
                        <article
                            key={stat.label}
                            className="space-y-5 bg-[#141414] border border-[#262626] rounded-sm p-6"
                        >
                            <h2 className="text-xs font-bold tracking-[0.15em] text-[#A3A3A3] uppercase">
                                {stat.label}
                            </h2>
                            <div className="space-y-2">
                                <p className="text-3xl font-black tracking-tight text-[#F97316]">
                                    {stat.value}
                                </p>
                                <p className={stat.detailClass}>
                                    {stat.detail}
                                </p>
                            </div>
                        </article>
                    ))}
                </div>

                <div className="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_minmax(18rem,1fr)]">
                    <section className="min-w-0 border border-[#262626] bg-[#141414]">
                        <header className="border-b border-[#262626] px-5 py-4">
                            <h2 className="text-sm font-black tracking-widest uppercase">
                                Recent Check-ins
                            </h2>
                        </header>
                        <div className="overflow-x-auto">
                            <table className="w-full min-w-[30rem] text-left text-sm">
                                <thead className="text-[10px] font-bold tracking-[0.15em] text-[#737373] uppercase">
                                    <tr>
                                        <th className="px-5 py-3">Member</th>
                                        <th className="px-5 py-3">Time</th>
                                        <th className="px-5 py-3">Plan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {recentCheckIns.map((checkIn) => (
                                        <tr
                                            key={`${checkIn.name}-${checkIn.time}`}
                                            className="border-t border-[#262626] text-[#D4D4D4]"
                                        >
                                            <td className="px-5 py-4 font-semibold">
                                                {checkIn.name}
                                            </td>
                                            <td className="px-5 py-4 tabular-nums text-[#A3A3A3]">
                                                {checkIn.time}
                                            </td>
                                            <td className="px-5 py-4">
                                                {checkIn.plan}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section className="border border-[#262626] bg-[#141414]">
                        <header className="flex items-center justify-between gap-3 border-b border-[#262626] px-5 py-4">
                            <h2 className="text-sm font-black tracking-widest uppercase">
                                Expiring Memberships
                            </h2>
                            <span className="shrink-0 text-[10px] font-bold tracking-wider text-[#F97316] uppercase">
                                Next 7 days
                            </span>
                        </header>
                        <ul className="divide-y divide-[#262626]">
                            {expiringMemberships.map((membership) => (
                                <li
                                    key={membership.name}
                                    className="flex items-center justify-between gap-4 px-5 py-4"
                                >
                                    <div className="min-w-0 space-y-1">
                                        <p className="truncate text-sm font-semibold text-[#F5F5F0]">
                                            {membership.name}
                                        </p>
                                        <p className="text-xs text-[#A3A3A3]">
                                            {membership.plan}
                                        </p>
                                    </div>
                                    <span className="shrink-0 text-xs font-bold text-[#F97316]">
                                        {membership.expiry}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    </section>
                </div>
            </main>
        </AppLayout>
    );
}
