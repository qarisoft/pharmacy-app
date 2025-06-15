import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
type Product = {
    id: number;
    name_ar: string;
    barcode: string;
    name_en: string;
};
type SalePointForm = {
    header: {
        customer_name: string;
        total_price: string;
        discount: number;
        addition: number;
    };
    items: { product_id: number; unit_id: number }[];
};
export default function Dashboard() {
    const a = usePage<{ products: Product[] }>();
    console.log(a.props.products);
    const {} = useForm<SalePointForm>({
        header: {
            customer_name: '',
            total_price: '',
            discount: 0,
            addition: 0,
        },
        items: [{ product_id: 1, unit_id: 1 }],
    });
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className=""></div>
            </div>
        </AppLayout>
    );
}
