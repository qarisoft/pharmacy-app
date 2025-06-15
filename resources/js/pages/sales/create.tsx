import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Minus, Plus } from 'lucide-react';
import { FormEventHandler, PropsWithChildren, useCallback, useEffect, useMemo, useRef, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

type Unit = {
    id: number;
    name: string;
    count: number;
};
type Product = {
    id: number;
    name_ar: string;
    barcode: string;
    name_en: string;
    units: Unit[];
    unit_price: number;
};
type SaleItem = { product_id: number; unit_id: number; quantity: number; end_price: number; product: Product };
type SalePointForm = {
    header: {
        customer_name: string;
        note: string;
        end_price: number;
        discount: number;
        addition: number;
    };
    items: SaleItem[];
};
export default function CreateSale() {
    const {
        props: { products: allProducts },
    } = usePage<{ products: Product[] }>();

    const { data, setData, post } = useForm<SalePointForm>({
        header: {
            customer_name: '',
            note: '',
            end_price: 0,
            discount: 0,
            addition: 0,
        },
        items: [],
    });
    const [showMenu, setShow] = useState<boolean>(false);
    const [open, onOnchange] = useState<boolean>(false);
    const [quantity, setQuantity] = useState<number>(1);
    const [unit, setUnit] = useState<Unit | undefined>(undefined);
    const [product, setProduct] = useState<Product | undefined>();

    const [search, setSearch] = useState<string>('');

    // const menuRef = useRef<HTMLDivElement>(null) as MutableRefObject<HTMLDivElement>;
    const menuRef = useRef<HTMLDivElement>(null);
    const inputRef = useRef<HTMLInputElement>(null);
    const quantityRef = useRef<HTMLInputElement>(null);
    const unitRef = useRef<HTMLButtonElement>(null);
    const addButtonRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (product) {
            setUnit(product.units[0]);
        }
    }, [product]);

    const onAdd = useCallback(
        (e: React.MouseEvent<HTMLDivElement, MouseEvent>) => {
            e.preventDefault();
            e.stopPropagation();
            if (product && unit) {
                setData((d) => {
                    return {
                        header: d.header,
                        items: [
                            ...d.items,
                            {
                                product_id: product.id,
                                unit_id: unit.id,
                                quantity: quantity,
                                end_price: quantity * product.unit_price * unit.count,
                                product: product,
                            },
                        ],
                    };
                });
                setProduct(undefined);
                setSearch('');
                inputRef.current?.focus();
                setShow(true);
                setQuantity(1);
            }
        },
        [product, quantity, setData, unit],
    );
    // const isAddButtonDisabled = useMemo(() => !product, [product]);

    const onSelectChange = useCallback(
        (value: string) => {
            const u = product?.units.find((u) => u.id == Number(value));
            if (u) {
                setUnit(u);
            }
        },
        [product],
    );
    const onQuantityKeyDow = useCallback((e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key == 'Enter') {
            unitRef.current?.focus();
        }
    }, []);
    const onUnitKeyDow = useCallback((e: React.KeyboardEvent<HTMLButtonElement>) => {
        e.preventDefault();
        if (e.key == 'Enter') {
            addButtonRef.current?.focus();
        }
    }, []);

    const onQuantityChange = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
        const a = Number(e.target.value);
        if (a > 0) {
            setQuantity(a);
        }
    }, []);
    const unitDisabled = useMemo(() => !unit, [unit]);

    const getUnit = (item: SaleItem) => {
        return item.product.units.find((it) => it.id == item.unit_id);
    };
    const saleTotal = useMemo(() => {
        let a = 0;
        data.items.forEach((item) => {
            const ep = (getUnit(item)?.count ?? 0) * item.product.unit_price * item.quantity;
            a += ep;
        });
        // a-=data.header.discount
        return a;
    }, [data.items]);

    const saleUnitTotal = useMemo(() => {
        let a = 0;
        data.items.forEach((item) => {
            const ep = (getUnit(item)?.count ?? 0) * item.product.unit_price;
            a += ep;
        });
        return a;
    }, [data.items]);

    const onItemDelete = useCallback(
        (index: number) => {
            setData((d: SalePointForm) => {
                return {
                    header: d.header,
                    items: d.items.filter((it, itIndex) => itIndex != index),
                };
            });
        },
        [setData],
    );

    const products = useMemo(() => {
        return allProducts.filter((p) => {
            if (p.unit_price <= 0) {
                return false;
            }
            if (!search) {
                return true;
            }
            if (p.name_ar && p.name_ar.trim().startsWith(search.trim())) {
                return true;
            }
            if (p.name_en && p.name_en.trim().startsWith(search.trim())) {
                return true;
            }
            return !!(p.barcode && p.barcode.startsWith(search));
        });
    }, [allProducts, search]);

    const onKeyDown = useCallback(
        (k: React.KeyboardEvent<HTMLDivElement>) => {
            if (k.key == 'ArrowDown') {
                if (product) {
                    const ind = products.indexOf(product);
                    const nextInd = ind + 1;
                    if (nextInd < products.length - 1) {
                        setProduct(products[nextInd]);
                        menuRef.current?.scrollBy({ top: 42 });
                    }
                } else {
                    setProduct(products[0]);
                }
            }
            if (k.key == 'ArrowUp') {
                if (product) {
                    const ind = products.indexOf(product);
                    const nextInd = ind - 1;
                    if (nextInd > -1) {
                        setProduct(products[nextInd]);
                        menuRef.current?.scrollBy({ top: -42 });
                    }
                } else {
                    setProduct(products[0]);
                }
            }
            if (k.key == 'Enter') {
                if (product) {
                    if (!showMenu) {
                        if (search != product.name_ar) {
                            setShow(true);
                        } else {
                            quantityRef.current?.focus();
                        }
                    } else {
                        setSearch(product.name_ar);
                        setShow(false);
                    }
                } else {
                    if (showMenu) {
                        if (products.length == 1) {
                            setProduct(products[0]);
                            setSearch(products[0].name_ar);
                            quantityRef.current?.focus();
                            setShow(false);
                        } else {
                            setShow(false);
                        }
                    } else {
                        if (!showMenu) {
                            setShow(true);
                        }
                    }
                }
            }
        },
        [product, products, search, showMenu],
    );

    const onProductOptionSelected = useCallback(
        (p: Product) => {
            setProduct(p);
            setSearch(p.name_ar);
            setShow(false);
            quantityRef.current?.focus();
        },
        [setProduct, setSearch, setShow],
    );

    const onSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('sales.store'), {
            // preserveState:true,
            // onSuccess:()=>{
            //     reset('items')
            //     reset('header')
            // }
        });
    };
    return (
        <div>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div
                    className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
                    onClick={() => {
                        setShow(false);
                    }}
                >
                    <div>
                        <div className="flex justify-start gap-2">
                            <div className="">
                                <FormInputGroup label={'product'}>
                                    <div className="relative">
                                        <input
                                            onKeyDown={onKeyDown}
                                            key={'a2'}
                                            autoFocus
                                            ref={inputRef}
                                            value={search}
                                            onFocus={() => setShow(true)}
                                            onChange={(e) => {
                                                setSearch(e.target.value);
                                            }}
                                            className={'z-20 w-[400px] rounded-lg border p-1'}
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                e.preventDefault();
                                                setShow(true);
                                            }}
                                        />

                                        {showMenu && (
                                            <div ref={menuRef} className={'absolute z-20 max-h-[250px] w-full overflow-y-auto bg-background'}>
                                                {products.map((p) => (
                                                    <div
                                                        onClick={() => onProductOptionSelected(p)}
                                                        title={p.name_ar}
                                                        key={`op-${p.id}`}
                                                        className={cn(
                                                            'mb-[1px] w-full border p-2 hover:cursor-pointer hover:bg-accent',
                                                            product?.id == p.id ? 'bg-gray-300' : '',
                                                        )}
                                                    >
                                                        {p.name_ar}
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                </FormInputGroup>
                            </div>
                            <div className="flex flex-1 gap-2">
                                <FormInputGroup label={'quantity'}>
                                    <Input
                                        onKeyDown={onQuantityKeyDow}
                                        ref={quantityRef}
                                        id={'quantity'}
                                        key={'quantity-input'}
                                        disabled={!product}
                                        type={'number'}
                                        className={'border p-1'}
                                        value={quantity}
                                        onChange={onQuantityChange}
                                    />
                                </FormInputGroup>
                                <FormInputGroup label={'unit'}>
                                    <Select
                                        defaultValue={unit?.id.toString()}
                                        value={unit?.id.toString()}
                                        open={open}
                                        onOpenChange={onOnchange}
                                        disabled={unitDisabled}
                                        onValueChange={onSelectChange}
                                        dir={'rtl'}
                                    >
                                        <SelectTrigger onKeyDown={onUnitKeyDow} ref={unitRef} className="w-[180px]">
                                            <SelectValue placeholder="Select a unit" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {product?.units.map((u) => (
                                                <SelectItem key={`unit-${u.id}`} value={u.id.toString()}>
                                                    {u.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </FormInputGroup>

                                <div className="">
                                    <div className="opacity-0">s</div>
                                    <input ref={addButtonRef} onClick={onAdd} type={'button'} value={'add'} />
                                </div>
                            </div>
                        </div>
                        {showMenu && <div className="h-40"></div>}

                        <SaleDataTable
                            discount={data.header.discount}
                            saleTotal={saleTotal}
                            saleUnitTotal={saleUnitTotal}
                            items={data.items}
                            getUnit={getUnit}
                            onItemDelete={onItemDelete}
                            updateRowQuantity={(by, i) => {
                                setData((d) => {
                                    return {
                                        header: d.header,
                                        items: d.items.map((itm, itmIndex) => {
                                            if (itmIndex === i) {
                                                if (itm.quantity + by <= 0) {
                                                    return itm;
                                                }
                                                return { ...itm, quantity: itm.quantity + by };
                                            }
                                            return itm;
                                        }),
                                    };
                                });
                            }}
                            updateRowUnit={(v, i) => {
                                setData((d) => {
                                    return {
                                        header: d.header,
                                        items: d.items.map((itm, itmIndex) => {
                                            if (itmIndex === i) {
                                                return { ...itm, unit_id: Number(v) };
                                            }
                                            return itm;
                                        }),
                                    };
                                });
                            }}
                        />

                        <div className="flex p-3">
                            <Button onClick={onSubmit}>Save</Button>
                        </div>
                    </div>
                </div>
            </AppLayout>
        </div>
    );
}

function SaleDataTable({
    items,
    getUnit,
    onItemDelete,
    saleUnitTotal,
    saleTotal,
    discount,
    updateRowUnit,
    updateRowQuantity,
}: {
    discount: number;
    saleTotal: number;
    saleUnitTotal: number;
    items: SaleItem[];
    getUnit: (item: SaleItem) => Unit | undefined;
    onItemDelete: (index: number) => void;
    updateRowUnit: (v: string, i: number) => void;
    updateRowQuantity: (by: -1 | 1, i: number) => void;
}) {
    return (
        <div>
            <div className="h-10"></div>
            <Table>
                {/*<TableCaption>A list of your recent invoices.</TableCaption>*/}
                <TableHeader>
                    <TableRow>
                        <TableHead className="w-[100px]">product</TableHead>
                        <TableHead className={'text-center'}>quantity</TableHead>
                        <TableHead className={'text-center'}>unit</TableHead>
                        <TableHead className={'text-center'}>unit price</TableHead>
                        <TableHead className={'text-center'}>total Price</TableHead>
                        <TableHead className="pe-5 text-end">...</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {items.map((item, index) => (
                        <TableRow key={`data-item-${item.product_id}-${index}`}>
                            <TableCell className="font-medium">{item.product.name_ar}</TableCell>
                            <TableCell>
                                <div className={'flex items-center justify-center gap-1'}>
                                    <Plus onClick={() => updateRowQuantity(1, index)} size={20} className={'rounded border hover:cursor-pointer'} />
                                    <div className={'px-1 select-none'}>{item.quantity}</div>
                                    <Minus onClick={() => updateRowQuantity(-1, index)} size={20} className={'rounded border hover:cursor-pointer'} />
                                </div>
                            </TableCell>

                            <TableCell className={'flex justify-center'}>
                                <div>
                                    {item.product.units.length > 1 ? (
                                        <Select value={item.unit_id.toString()} onValueChange={(v) => updateRowUnit(v, index)}>
                                            <SelectTrigger>
                                                <SelectValue />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {item.product.units.map((u) => (
                                                    <SelectItem key={`${u.id}-unit-it`} value={u.id.toString()}>
                                                        <div className="select-none">{u.name}</div>
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                    ) : (
                                        <div className={'select-none'}>{getUnit(item)?.name}</div>
                                    )}
                                </div>
                            </TableCell>

                            <TableCell className={'text-center select-none'}>{(getUnit(item)?.count ?? 0) * item.product.unit_price}</TableCell>
                            <TableCell className={'text-center select-none'}>
                                {(getUnit(item)?.count ?? 0) * item.product.unit_price * item.quantity}
                            </TableCell>
                            <TableCell className="text-end" onClick={() => onItemDelete(index)}>
                                <Button variant={'link'} className={'px-1.5 py-1 text-red-700'}>
                                    del
                                </Button>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TableCell colSpan={3}>Total</TableCell>
                        <TableCell className="text-center">{saleUnitTotal}</TableCell>
                        <TableCell className="text-center">{saleTotal}</TableCell>
                    </TableRow>
                </TableFooter>
            </Table>
            <div className="h-5"></div>
            <div className="border">
                <div className="flex gap-2 p-1">
                    <div className="">Cost Price :</div>
                    <div className="">{saleTotal}</div>
                </div>
                <div className="flex gap-2 p-1">
                    <div className="">Discount :</div>
                    <div className="">{discount}</div>
                </div>
                <div className="flex gap-2 p-1">
                    <div className="">End Price :</div>
                    <div className="">{saleTotal - discount}</div>
                </div>
            </div>
        </div>
    );
}

function FormInputGroup({ children, label }: PropsWithChildren<{ label: string }>) {
    return (
        <div className="">
            <div className="">{label}</div>
            <div className="h-1"></div>
            <div className="">{children}</div>
        </div>
    );
}
