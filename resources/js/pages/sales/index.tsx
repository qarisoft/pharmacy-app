import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, Product } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { Check, ChevronsUpDown } from 'lucide-react';
import * as React from 'react';
import { useCallback, useRef, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const frameworks = [
    {
        value: 'next.js',
        label: 'Next.js',
    },
    {
        value: 'sveltekit',
        label: 'SvelteKit',
    },
    {
        value: 'nuxt.js',
        label: 'Nuxt.js',
    },
    {
        value: 'remix',
        label: 'Remix',
    },
    {
        value: 'astro',
        label: 'Astro',
    },
];
// type Product = {
//     id:number,
//     name_ar:string,
//     barcode:string,
//     name_en:string
// }
// type SalePointForm={
//     header:{
//         customer_name:string,
//         total_price:string,
//         discount:number,
//         addition:number
//     },
//     items:{product_id:number,unit_id:number}[],
// }
export default function Dashboard() {
    const a = usePage();
    console.log(a.props.products);
    const ref = useRef<HTMLInputElement>(null);
    const [product,setProduct] = useState<Product|undefined>(undefined)
    const aa = useCallback(() => {
        if (product){
            ref.current?.focus();
        }
    },[product])
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="" onClick={() => router.get(route('sales.create'))}>
                    select
                </div>

                <A goNext={aa} setProduct={setProduct} />

                <Input ref={ref} type={'text'} />
            </div>
        </AppLayout>
    );
}

function A({ goNext,setProduct }: { goNext: () => void,setProduct:(p:Product)=>void }) {
    const [open, setOpen] = React.useState(false);
    const [value, setValue] = React.useState('');


    const {
        props: { products },
    } = usePage<{ products: Product[] }>();
    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
                <Button variant="outline" role="combobox" aria-expanded={open} className="w-[200px] justify-between">
                    {value ? products.find((p) => p.name_ar === value)?.name_ar : 'Select framework...'}
                    <ChevronsUpDown className="opacity-50" />
                </Button>
            </PopoverTrigger>
            <PopoverContent
                className="w-[200px] p-0"
            >
                <Command
                    onKeyDown={(k) => {
                        k.stopPropagation();
                        if (k.key == 'Enter') {
                            goNext();
                        }
                    }}

                >
                    <Input placeholder="Search framework..." className="h-9"
                                  onChange={(s)=>{
                                      console.log(s);
                                  }}

                    />
                    <CommandList>
                        <CommandEmpty>No framework found.</CommandEmpty>
                        {/*<CommandGroup>*/}
                            {products.map((product) => (
                                <CommandItem
                                    key={product.id}
                                    // value={product.name_ar}
                                    // onSelect={(currentValue) => {
                                    //     // setValue(currentValue === value ? '' : currentValue);
                                    //     if (currentValue!=product.id.toString()){
                                    //         setProduct(product)
                                    //     }
                                    //     setOpen(false);
                                    // }}
                                >
                                    {product.name_ar}
                                    <Check className={cn('ms-auto', value === product.name_ar ? 'opacity-100' : 'opacity-0')} />
                                </CommandItem>
                            ))}
                        {/*</CommandGroup>*/}
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
