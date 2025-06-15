import type { NavItem, NavItemGroup } from '@/types';
import { BookOpen, Folder, LayoutGrid } from 'lucide-react';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Products',
        href: '/products',
        icon: LayoutGrid,
    },
    {
        title: 'Sales',
        href: '/sales',
        icon: LayoutGrid,
    },
];

const mainNavItemsGroups: NavItemGroup[] = [
    {
        title: 'Dashboard',
        href: 'dashboard',
        icon: LayoutGrid,
        items: [],
    },
    {
        title: 'Products',
        href: 'products',
        icon: LayoutGrid,
        items: [
            {
                title: 'Index',
                href: 'products.index',
            },
            {
                title: 'Create',
                href: 'products.create',
            },
        ],
    },
    {
        title: 'Sales',
        href: 'sales',
        icon: LayoutGrid,
        items: [
            {
                title: 'Create',
                href: 'sales.create',
            },
            {
                title: 'Index',
                href: 'sales.index',
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export { footerNavItems, mainNavItems, mainNavItemsGroups };
