import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type PaginatedData, Product, SaleHeader } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import * as React from 'react';
import DataTable from '@/components/data-table/data-table';
import { ColumnDef } from '@tanstack/react-table';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { useLang } from '@/hooks/useLang';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];



type SaleHeaderObject=SaleHeader&{id:number}
export default function Dashboard({ pageData }: { pageData: PaginatedData<SaleHeaderObject> }) {

    const {__}=useLang()

    const columns: ColumnDef<SaleHeaderObject>[] = [
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
            accessorKey: 'end_price',
            header: __('total price'),
            cell: ({ row }) => <div className="capitalize">{row.original.end_price}</div>,
        },
        {
            accessorKey: 'discount',
            header: __('discount'),
            cell: ({ row }) => <div className="capitalize">{row.original.discount}</div>,
        },
        {
            accessorKey: 'note',
            header: __('note'),
            cell: ({ row }) => <div className="capitalize">{row.original.note}</div>,
        },

        {
            id: 'edit',
            enableHiding: false,
            cell: ({ row }) => {
                // const payment = row.original

                return (
                    <div className={'flex justify-end items-center'}>
                        <Button
                            onClick={() => {
                                router.get(route('sales.edit', row.original.id));
                            }}
                            variant={'outline'}
                            className={'px-3 py-2 text-xs'}
                        >
                            {__('edit')}
                        </Button>
                        <div
                            onClick={() => {
                                router.get(route('sales.destroy', row.original.id));
                            }}
                            className={'px-3 py-2 text-xs text-red-700'}
                        >
                            {__('del')}
                        </div>
                    </div>
                );
            },
        },

    ];

    console.log(pageData.data);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

                <DataTable pageData={pageData} columns={columns} path={'products'} />

                {/*<Input ref={ref} type={'text'} />*/}
            </div>
        </AppLayout>
    );
}


// function A({ goNext, setProduct }: {  goNext: () => void; setProduct: (p: Product | undefined) => void }) {
//     const [open, setOpen] = React.useState(false);
//     const [value, setValue] = React.useState('');
//
//     const {
//         props: { products: allProducts },
//     } = usePage<{ products: Product[] }>();
//
//     const products = useMemo(() => {
//         return allProducts.filter((p) => {
//             if (p.unit_price <= 0) {
//                 return false;
//             }
//             if (!value) {
//                 return true;
//             }
//             if (p.name_ar && p.name_ar.trim().includes(value.trim())) {
//                 return true;
//             }
//             if (p.name_en && p.name_en.trim().includes(value.trim())) {
//                 return true;
//             }
//             return !!(p.barcode && p.barcode.includes(value));
//         });
//     }, [allProducts, value]);
//
//     return (
//         <Popover open={open} onOpenChange={setOpen}>
//             <PopoverTrigger
//                 asChild
//                 autoFocus
//                 onKeyDown={(k) => {
//                     // k.stopPropagation();
//                     console.log(k.key);
//                     if (!open) {
//                         if (k.key == 'ArrowDown') {
//                             setOpen(true);
//                         }
//                         if (k.key == 'Enter') {
//                             goNext();
//                         }
//                     }
//                 }}
//             >
//                 <div className="relative w-fit">
//                     <Button variant="outline" role="combobox" aria-expanded={open} className="w-[300px] justify-between pe-2">
//                         {value ? value : 'product'}
//                         <div className="flex">
//                             <ChevronsUpDown className="opacity-50" />
//                         </div>
//                     </Button>
//                     {/*{product && (*/}
//                     {/*    <XCircle*/}
//                     {/*        size={16}*/}
//                     {/*        className="absolute top-1/2 left-2 z-20 -translate-y-1/2 bg-gray-300 text-red-700 hover:cursor-pointer"*/}
//                     {/*        onClick={(e) => {*/}
//                     {/*            e.stopPropagation();*/}
//                     {/*            setProduct(undefined);*/}
//                     {/*        }}*/}
//                     {/*    />*/}
//                     {/*)}*/}
//                 </div>
//             </PopoverTrigger>
//             <PopoverContent
//                 autoFocus
//                 onKeyDown={(k) => {
//                     // k.stopPropagation();
//                     console.log('s',k.key);
//                         if (k.key == 'Enter') {
//                             goNext();
//                         }
//                     if (!open) {
//                         if (k.key == 'ArrowDown') {
//                             setOpen(true);
//                         }
//                     }
//                 }}
//                 className="w-[300px] p-0">
//                 <Command>
//                     <Input
//                         placeholder="Search framework..."
//                         className="h-9"
//                         value={value}
//
//                         onChange={(s) => {
//                             // if (product){
//                             //     setProduct(undefined)
//                             // }
//                             setValue(s.target.value);
//                         }}
//                     />
//                     <CommandList>
//                         <CommandEmpty>No framework found.</CommandEmpty>
//                         {/*<CommandGroup>*/}
//                         {products.map((product) => (
//                             <CommandItem
//                                 key={product.id}
//                                 value={product.id.toString()}
//                                 onSelect={() => {
//                                     setProduct(product);
//                                     setOpen(false);
//                                     setValue("")
//                                 }}
//                             >
//                                 {product.name_ar}
//                                 <Check className={cn('ms-auto', value === product.name_ar ? 'opacity-100' : 'opacity-0')} />
//                             </CommandItem>
//                         ))}
//                         {/*</CommandGroup>*/}
//                     </CommandList>
//                 </Command>
//             </PopoverContent>
//         </Popover>
//     );
// }
