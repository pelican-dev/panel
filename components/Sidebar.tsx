"use client";

import * as React from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import {
    IconDashboard,
    IconServer,
    IconCpu,
    IconEgg,
    IconUsers,
    IconUserShield,
    IconKey,
    IconDatabase,
    IconGitFork,
    IconWebhook,
    IconHeartbeat,
    IconSettings,
} from '@tabler/icons-react';

type Tone = 'primary' | 'danger';
type NavItem = {
    href: string;
    label: string;
    icon: React.ComponentType<{ className?: string }>;
    count?: number;
    tone?: Tone;
};

const adminNavItems: {
    topLevel: NavItem[];
    server: NavItem[];
    user: NavItem[];
    advanced: NavItem[];
} = {
    topLevel: [
        { href: '/admin', label: 'Dashboard', icon: IconDashboard },
        { href: '/admin/settings', label: 'Settings', icon: IconSettings },
    ],
    server: [
        { href: '/admin/eggs', label: 'Eggs', icon: IconEgg, count: 15, tone: 'primary' as const },
        { href: '/admin/nodes', label: 'Nodes', icon: IconCpu, count: 1, tone: 'primary' as const },
        { href: '/admin/servers', label: 'Servers', icon: IconServer, count: 2, tone: 'primary' as const },
    ],
    user: [
        { href: '/admin/roles', label: 'Roles', icon: IconUserShield, count: 1, tone: 'primary' as const },
        { href: '/admin/users', label: 'Users', icon: IconUsers, count: 1, tone: 'primary' as const },
    ],
    advanced: [
        { href: '/admin/health', label: 'Health', icon: IconHeartbeat, count: 3, tone: 'danger' as const },
        { href: '/admin/api-keys', label: 'API Keys', icon: IconKey, count: 1, tone: 'primary' as const },
        { href: '/admin/database-hosts', label: 'Database Hosts', icon: IconDatabase },
        { href: '/admin/mounts', label: 'Mounts', icon: IconGitFork },
        { href: '/admin/webhooks', label: 'Webhooks', icon: IconWebhook },
        { href: '/admin/settings', label: 'Settings', icon: IconSettings },
    ],
};

export const Sidebar = () => {
    const pathname = usePathname();
    const [collapsed, setCollapsed] = React.useState(false);

    // Collapse by default on small screens and listen for toggle events
    React.useEffect(() => {
        const mq = window.matchMedia('(max-width: 1024px)'); // lg breakpoint
        const apply = () => setCollapsed(mq.matches);
        apply();
        mq.addEventListener('change', apply);

        const onToggle = () => setCollapsed((c) => !c);
        window.addEventListener('toggle-sidebar', onToggle as EventListener);

        return () => {
            mq.removeEventListener('change', apply);
            window.removeEventListener('toggle-sidebar', onToggle as EventListener);
        };
    }, []);

    const linkClasses = (href: string) => {
        const active = pathname?.startsWith(href);
        return [
            'fi-sidebar-item-button relative flex items-center rounded-lg outline-none transition duration-75 h-10',
            collapsed ? 'justify-center gap-0 w-10 px-2' : 'justify-start gap-3 w-full max-w-[18rem] px-6',
            active ? 'bg-gray-100 dark:bg-white/5' : 'hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5',
        ].join(' ');
    };

    const iconClasses = (active: boolean) =>
        active ? 'h-6 w-6 text-primary-600 dark:text-primary-400' : 'h-6 w-6 text-gray-400 dark:text-gray-500';

    const CountBadge = ({ count, tone }: { count?: number; tone?: Tone }) => {
        if (count == null) return null;
        const toneClasses =
            tone === 'danger'
                ? 'bg-destructive/10 text-destructive ring-destructive/30'
                : 'bg-primary/10 text-primary ring-primary/30';
        return (
            <span className="ml-auto">
                <span className={`fi-badge flex items-center justify-center gap-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 ${toneClasses}`}>
                    <span className="grid">
                        <span className="truncate">{count}</span>
                    </span>
                </span>
            </span>
        );
    };

    return (
        <aside
            className="fi-sidebar fixed inset-y-0 start-0 z-30 flex h-screen flex-col content-start transition-all shadow-xl ring-1 ring-gray-950/5 dark:ring-white/10 rtl:-translate-x-0 lg:sticky lg:z-0 lg:shadow-none lg:ring-0 lg:transition-none"
            style={{
                width: collapsed ? '4rem' : 'var(--sidebar-width)',
                backgroundColor: 'hsl(var(--background))',
                color: 'hsl(var(--foreground))',
            }}
        >
            <div className="h-16 flex items-center px-3 border-b bg-card/80 backdrop-blur supports-[backdrop-filter]:bg-card/60 shrink-0 justify-between">
                {!collapsed && (
                    <h2 className="text-xl font-semibold whitespace-nowrap leading-none">Pelican Panel</h2>
                )}
                <button
                    type="button"
                    aria-label={collapsed ? 'Expand sidebar' : 'Collapse sidebar'}
                    title={collapsed ? 'Expand' : 'Collapse'}
                    className="fi-icon-btn h-9 w-9 grid place-items-center rounded-md text-gray-500 hover:text-gray-400 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-white/5"
                    onClick={() => setCollapsed((c) => !c)}
                >
                    {/* Simple chevron */}
                    <svg className={`h-5 w-5 transition ${collapsed ? '' : 'rotate-180'}`} viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fillRule="evenodd" d="M7.47 14.53a.75.75 0 0 1 0-1.06L10.94 10 7.47 6.53a.75.75 0 1 1 1.06-1.06l4 4a.75.75 0 0 1 0 1.06l-4 4a.75.75 0 0 1-1.06 0Z" clipRule="evenodd" />
                    </svg>
                </button>
            </div>
            <TooltipProvider>
            <nav className="flex flex-col gap-2 px-3">
                <div>
                    <ul className="flex flex-col gap-1">
                        {adminNavItems.topLevel.map((item) => {
                            const active = pathname?.startsWith(item.href) ?? false;
                            return (
                                <li key={item.href} className="fi-sidebar-item">
                                    {collapsed ? (
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Link href={item.href} className={linkClasses(item.href)}>
                                                    <item.icon className={iconClasses(active)} />
                                                </Link>
                                            </TooltipTrigger>
                                            <TooltipContent side="right">{item.label}</TooltipContent>
                                        </Tooltip>
                                    ) : (
                                        <Link href={item.href} className={linkClasses(item.href)}>
                                            <item.icon className={iconClasses(active)} />
                                            <span className={`flex-1 truncate text-sm font-medium ${active ? 'text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200'}`}>{item.label}</span>
                                        </Link>
                                    )}
                                </li>
                            );
                        })}
                    </ul>
                </div>
                <div>
                    {!collapsed && (
                        <div className="px-2 py-2">
                            <h3 className="text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">Server</h3>
                        </div>
                    )}
                    <ul className="flex flex-col gap-1">
                        {adminNavItems.server.map((item) => {
                            const active = pathname?.startsWith(item.href) ?? false;
                            return (
                                <li key={item.href} className="fi-sidebar-item">
                                    {collapsed ? (
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Link href={item.href} className={linkClasses(item.href)}>
                                                    <item.icon className={iconClasses(active)} />
                                                </Link>
                                            </TooltipTrigger>
                                            <TooltipContent side="right">{item.label}</TooltipContent>
                                        </Tooltip>
                                    ) : (
                                        <Link href={item.href} className={linkClasses(item.href)}>
                                            <item.icon className={iconClasses(active)} />
                                            <span className={`flex-1 truncate text-sm font-medium ${active ? 'text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200'}`}>{item.label}</span>
                                            <CountBadge count={item.count} tone={item.tone} />
                                        </Link>
                                    )}
                                </li>
                            );
                        })}
                    </ul>
                </div>
                <div>
                    {!collapsed && (
                        <div className="px-2 py-2">
                            <h3 className="text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">User</h3>
                        </div>
                    )}
                    <ul className="flex flex-col gap-1">
                        {adminNavItems.user.map((item) => {
                            const active = pathname?.startsWith(item.href) ?? false;
                            return (
                                <li key={item.href} className="fi-sidebar-item">
                                    {collapsed ? (
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Link href={item.href} className={linkClasses(item.href)}>
                                                    <item.icon className={iconClasses(active)} />
                                                </Link>
                                            </TooltipTrigger>
                                            <TooltipContent side="right">{item.label}</TooltipContent>
                                        </Tooltip>
                                    ) : (
                                        <Link href={item.href} className={linkClasses(item.href)}>
                                            <item.icon className={iconClasses(active)} />
                                            <span className={`flex-1 truncate text-sm font-medium ${active ? 'text-primary-600 dark:text-primary-400' : ''}`}>{item.label}</span>
                                            <CountBadge count={item.count} tone={item.tone} />
                                        </Link>
                                    )}
                                </li>
                            );
                        })}
                    </ul>
                </div>
                <div>
                    {!collapsed && (
                        <div className="px-2 py-2">
                            <h3 className="text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">Advanced</h3>
                        </div>
                    )}
                    <ul className="flex flex-col gap-1">
                        {adminNavItems.advanced.map((item) => {
                            const active = pathname?.startsWith(item.href) ?? false;
                            return (
                                <li key={item.href} className="fi-sidebar-item">
                                    {collapsed ? (
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Link href={item.href} className={linkClasses(item.href)}>
                                                    <item.icon className={iconClasses(active)} />
                                                </Link>
                                            </TooltipTrigger>
                                            <TooltipContent side="right">{item.label}</TooltipContent>
                                        </Tooltip>
                                    ) : (
                                        <Link href={item.href} className={linkClasses(item.href)}>
                                            <item.icon className={iconClasses(active)} />
                                            <span className={`flex-1 truncate text-sm font-medium ${active ? 'text-primary-600 dark:text-primary-400' : ''}`}>{item.label}</span>
                                            <CountBadge count={item.count} tone={item.tone} />
                                        </Link>
                                    )}
                                </li>
                            );
                        })}
                    </ul>
                </div>
            </nav>
            </TooltipProvider>
        </aside>
    );
};
