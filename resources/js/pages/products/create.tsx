import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';
// import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';
import { FormEventHandler } from 'react';
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

interface CreateProps {
    name_ar: string;
    name_en: string;
    barcode: Str;
    scientific_name: Str;
}

export default function CreateProduct() {
    const { data, setData, post, processing, errors, reset } = useForm<Required<CreateProps>>({
        name_ar: '',
        name_en: '',
        barcode: '',
        scientific_name: '',
    });
    const onSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('products.store'), {
            // onFinish: () => reset('password'),
        });
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-4">
                <form onSubmit={onSubmit} className="">
                    <div className="grid grid-cols-2 gap-2">
                        <div className="grid gap-2">
                            <Label htmlFor="name_ar">name ar</Label>
                            <Input
                                id="name_ar"
                                required
                                type="text"
                                autoFocus
                                tabIndex={1}
                                value={data.name_ar}
                                onChange={(e) => setData('name_ar', e.target.value)}
                                placeholder="name_ar"
                            />
                            <InputError message={errors.name_ar} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="name_en">name_en</Label>
                            <Input
                                id="name_en"
                                type="text"
                                tabIndex={1}
                                autoComplete="name_en"
                                value={data.name_en}
                                onChange={(e) => setData('name_en', e.target.value)}
                                placeholder="name_en"
                            />
                            <InputError message={errors.name_ar} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="barcode">Barcode</Label>
                            <Input
                                id="barcode"
                                type="text"
                                tabIndex={1}
                                autoComplete="barcode"
                                value={data.barcode}
                                onChange={(e) => setData('barcode', e.target.value)}
                                placeholder="barcode"
                            />
                            <InputError message={errors.name_ar} />
                        </div>
                    </div>

                    <div className="p-4">
                        <Button>Save</Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
