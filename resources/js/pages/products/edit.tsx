import AppLayout from '@/layouts/app-layout';
import { Head, router, useForm, usePage } from '@inertiajs/react';
// import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import type { BreadcrumbItem } from '@/types';
import { FormEventHandler, useCallback, useEffect, useId, useState } from 'react';
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

type MeasureUnit = {
    id: number;
    name: string;
    count: number;
    sell_price: number;
    cost_price: number;
};

interface CreateProps {
    name_ar: string;
    name_en: string;
    barcode: Str;
    scientific_name: Str;
    units: MeasureUnit[];
}

export default function CreateProduct({ product }: { product: Product }) {
    const { data, setData, post, processing, errors, reset } = useForm<Required<CreateProps>>({
        name_ar: product.name_ar,
        name_en: product.name_en,
        barcode: product.barcode,
        scientific_name: product.scientific_name,
        units: [],
    });

    const addUnit = useCallback(() => {
        setData('units', [
            ...data.units,
            {
                id: data.units.length + 1,
                name: '',
                count: 1,
                sell_price: 0,
                cost_price: 0,
            },
        ]);
    }, [data.units, setData]);
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
                                required
                                autoFocus
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
                                required
                                autoFocus
                                tabIndex={1}
                                autoComplete="barcode"
                                value={data.barcode}
                                onChange={(e) => setData('barcode', e.target.value)}
                                placeholder="barcode"
                            />
                            <InputError message={errors.name_ar} />
                        </div>
                    </div>
                    <div className="py-10">
                        <div className="grid gap-2">
                            <div className="flex items-center gap-10">
                                <Label htmlFor="units">Units</Label>
                                <Button onClick={addUnit} variant={'link'}>
                                    add
                                </Button>
                            </div>

                            {/*<InputError message={errors.name_ar} />*/}

                            {data.units.map((unit) => (
                                <SelectWithSearch key={unit.id} options={[]} onChange={() => {}} label={'unit'} />
                            ))}
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

// type ErrorProps = { error: string | undefined };
// type onChangeProps = { onChange: (a: string | number) => void };
type labelProps = { label: string };
// type valueProps = { value: string | undefined };
//
type SelectSearchProps<T> = {
    options: T[];
    onChange: (id: number) => void;
} & labelProps;

export function SelectWithSearch<T extends { id: string | number; name: string }>({ options, onChange, label }: SelectSearchProps<T>) {
    const { url } = usePage();
    const inputId = useId();
    const search = (searchKey: string | null) => {
        // router.
        router.get(
            url,
            {
                search: searchKey,
            },
            {
                preserveState: true,
                showProgress: false,
                // only:['cities']
            },
        );
    };
    const [open, setOpen] = useState(false);
    const [value, setValue] = useState('');
    console.log(error);

    const onChose = (id: string, name: string) => {
        setValue(name);
        onChange(Number(id));
        search(null);
        setOpen(false);
    };
    const callBack = useCallback(
        (a: MouseEvent) => {
            const el = a.target as Element;
            const condition = el.getAttribute('id') == inputId;

            if (condition) {
                setOpen((op) => !op);
                return;
            }

            const id = el.getAttribute('data-select-id');
            const name = el.getAttribute('data-select-name');

            if (id && name) {
                onChose(id, name);
            } else {
                setOpen(false);
            }
        },
        [inputId, open, setOpen],
    );
    useEffect(() => {
        window.addEventListener('click', callBack);
        return () => {
            window.removeEventListener('click', callBack);
        };
    }, []);

    // const { t } = useLang()
    // console.log(options);

    return (
        <div className="relative">
            <Label htmlFor={label}>{label.replaceAll('_id', '')}</Label>
            <div className="h-1"></div>
            <Input
                id={inputId}
                className={cn('w-full ring-0 focus-visible:ring-0', error ? 'border-red-400' : '')}
                value={value ?? ''}
                onChange={(a) => {
                    setValue(a.target.value);
                    search(a.target.value);
                }}
                placeholder={t(label)}
            />
            <InputError message={error} className="mt-2" />

            {open && (
                <div className="absolute mt-1 max-h-[200px] w-full overflow-auto rounded border bg-white p-2 shadow-lg">
                    {options.length > 0 ? (
                        options.map((c) => (
                            <div data-select-id={c.id} data-select-name={c.name} className="border-b py-1.5" key={'a' + c.id}>
                                {c.name}
                            </div>
                        ))
                    ) : (
                        <div className="text-center">No Result</div>
                    )}
                </div>
            )}
        </div>
    );
}
