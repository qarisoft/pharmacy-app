import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
// import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import DataTable from '@/components/data-table/data-table';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import type { BreadcrumbItem, PaginatedData } from '@/types';
import { ColumnDef } from '@tanstack/react-table';
// import { Payment } from '@/pages/products/page';

// import { DataTableDemo } from './page';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
type Str = string | undefined;
type Int = number | undefined;
type Product = {
    id: number;
    name_ar: string;
    name_en: string;
    barcode: Str;
    scientific_name: Str;
    company_id: Int;
    created_by: Int;
    sell_price: Int;
    cost_price: Int;
    unit_price: Int;
    created_at: string;
    updated_at: string;
};

export default function Dashboard({ pageData }: { pageData: PaginatedData<Product> }) {
    console.log(pageData);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="max-h-[calc(100vh-5rem)]  overflow-y-auto ">
            <div className="p-4">
                <DataTable pageData={pageData} columns={columns} path={'products'} />
            </div>
            </div>
        </AppLayout>
    );
}

const columns: ColumnDef<Product>[] = [
    {
        id: 'select',
        header: ({ table }) => (
            <Checkbox
                checked={table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate')}
                onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
                aria-label="Select all"
            />
        ),
        cell: ({ row }) => (
            <Checkbox checked={row.getIsSelected()} onCheckedChange={(value) => row.toggleSelected(!!value)} aria-label="Select row" />
        ),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name_ar',
        header: 'name ar',
        cell: ({ row }) => <div className="capitalize">{row.getValue('name_ar')}</div>,
    },
    {
        accessorKey: 'name_en',
        header: 'name en',
        cell: ({ row }) => <div className="capitalize">{row.getValue('name_en')}</div>,
    },
    {
        accessorKey: 'barcode',
        header: 'barcode',
        cell: ({ row }) => <div className="capitalize">{row.getValue('barcode')}</div>,
    },

    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            // const payment = row.original

            return (
                <div className={'flex justify-end'}>
                    <Button
                        onClick={() => {
                            router.get(route('products.edit', row.original.id));
                        }}
                        variant={'outline'}
                        className={'px-3 py-2 text-xs'}
                    >
                        edit
                    </Button>
                </div>
            );
        },
    },
];
