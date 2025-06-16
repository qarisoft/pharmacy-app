import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, Product, SaleHeader, SaleItem, SalePointForm, Unit } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import { Check, ChevronsUpDown, Minus, Plus, XCircle } from 'lucide-react';
import * as React from 'react';
import { FormEventHandler, PropsWithChildren, useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { useLang } from '@/hooks/useLang';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function SaleForm({autofocus,header,items,path,header_id}:{
    autofocus:boolean,
    header?:SaleHeader,
    items?:SaleItem[],
    path:string
    header_id?:number

}) {
    const {
        props: { products: allProducts },
    } = usePage<{ products: Product[] }>();

    // log(allProducts)
    // console.log(allProducts);

    const { data, setData, post,put } = useForm<SalePointForm>({
        header: {
            customer_name: header?.customer_name??'',
            note: header?.note??'',
            end_price: header?.end_price??0,
            discount: header?.discount??0,
            addition: header?.addition??0,
        },
        items: items?? [],
    });
    const onSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        if (path=='update'){
            put(route(`sales.${path}`,header_id), {});

        }else {

            post(route(`sales.${path}`), {});
        }
    };

    // const [showMenu, setShow] = useState<boolean>(false);
    const [open, onOnchange] = useState<boolean>(false);
    const [productOpen, setProductOpen] = React.useState<boolean>(autofocus);
    const [quantity, setQuantity] = useState<number>(1);
    const [unit, setUnit] = useState<Unit | undefined>(undefined);
    const [product, setProduct] = useState<Product | undefined>();

    const inputRef = useRef<HTMLInputElement>(null);
    const quantityRef = useRef<HTMLInputElement>(null);
    const unitRef = useRef<HTMLButtonElement>(null);
    const addButtonRef = useRef<HTMLInputElement>(null);
    const saveButtonRef = useRef<HTMLButtonElement>(null);

    const [value, setValue] = React.useState('');


    const products = useMemo(() => {
        return allProducts.filter((p) => {
            if (p.unit_price <= 0) {
                return false;
            }
            if (!value) {
                return true;
            }
            if (p.name_ar && p.name_ar.trim().includes(value.trim())) {
                return true;
            }
            if (p.name_en && p.name_en.trim().includes(value.trim())) {
                return true;
            }
            return !!(p.barcode && p.barcode.includes(value));
        });
    }, [allProducts, value]);


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
                inputRef.current?.focus();
                setQuantity(1);
                setProductOpen(true);
            }
        },
        [product, quantity, setData, unit],
    );

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
        if (e.key == 'ArrowDown') {
            onOnchange(true);
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

    const {t,__}=useLang()




    return (
        <div>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                    <div>
                        <div className="flex justify-start gap-2">
                            <div className="">
                                <FormInputGroup label={__('product')}>
                                    <Popover open={productOpen} onOpenChange={setProductOpen} key={'product-id-select'}>
                                        <PopoverTrigger
                                            asChild
                                            autoFocus
                                            onKeyDown={(k) => {
                                                // k.stopPropagation();
                                                console.log(k.key);
                                                if (k.key == 'Enter') {
                                                    quantityRef.current?.focus();
                                                }
                                                if (!open) {
                                                    // if (k.key == 'ArrowDown') {
                                                    //     setOpen(true);
                                                    // }
                                                }
                                            }}
                                        >
                                            <div className="relative w-fit">
                                                <Button
                                                    variant="outline"
                                                    role="combobox"
                                                    aria-expanded={open}
                                                    className="w-[300px] justify-between pe-2"
                                                >
                                                    {product ? product.name_ar : 'Search'}
                                                    <div className="flex">
                                                        <ChevronsUpDown className="opacity-50" />
                                                    </div>
                                                </Button>
                                                {product && (
                                                    <XCircle
                                                        size={16}
                                                        className="absolute top-1/2 left-2 z-20 -translate-y-1/2 bg-white text-red-700 hover:cursor-pointer"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            setProduct(undefined);
                                                        }}
                                                    />
                                                )}
                                            </div>
                                        </PopoverTrigger>
                                        <PopoverContent
                                            key={'product-id-select-0'}
                                            autoFocus
                                            onKeyDown={(k) => {
                                                // k.stopPropagation();
                                                console.log('s', k.key);
                                                if (k.key == 'Enter') {
                                                    quantityRef.current?.focus();
                                                }
                                                if (!open) {
                                                    if (k.key == 'ArrowDown') {
                                                        setProductOpen(true);
                                                    }
                                                }
                                            }}
                                            className="w-[300px] p-0"
                                        >
                                            <Command>
                                                <Input
                                                    placeholder="Search framework..."
                                                    className="h-9"
                                                    value={value}
                                                    onChange={(s) => {
                                                        if (product) {
                                                            setProduct(undefined);
                                                        }
                                                        setValue(s.target.value);
                                                    }}
                                                />
                                                <CommandList>
                                                    <CommandEmpty>No framework found.</CommandEmpty>
                                                    {/*<CommandGroup>*/}
                                                    {products.map((product) => (
                                                        <CommandItem
                                                            key={product.id}
                                                            value={product.id.toString()}
                                                            onSelect={() => {
                                                                setProduct(product);
                                                                setValue('');
                                                                // setOpen(false);
                                                            }}
                                                        >
                                                            {product.name_ar}
                                                            <Check
                                                                className={cn('ms-auto', value === product.name_ar ? 'opacity-100' : 'opacity-0')}
                                                            />
                                                        </CommandItem>
                                                    ))}
                                                    {/*</CommandGroup>*/}
                                                </CommandList>
                                            </Command>
                                        </PopoverContent>
                                    </Popover>
                                </FormInputGroup>
                            </div>
                            <div className="flex flex-1 gap-2">
                                <FormInputGroup label={__('quantity')}>
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
                                <FormInputGroup label={__('unit')}>
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
                                                    <div className="flex gap-3">
                                                        <span>{u.name}</span>
                                                        <span>{u.count * product?.unit_price}</span>
                                                        <span>{'RYS'}</span>
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </FormInputGroup>

                                <div className="">
                                    <div className="opacity-0">s</div>
                                    <input
                                        ref={addButtonRef}
                                        onClick={onAdd}
                                        type={'button'}
                                        disabled={!product}
                                        className={'mt-1 rounded border px-2 py-1 disabled:opacity-50'}
                                        value={t('Add')}
                                    />
                                </div>
                            </div>
                        </div>

                        <SaleDataTable
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
                        <Details
                            saleTotal={saleTotal}
                            header={data.header}
                            setDiscount={(a) =>
                                setData((d) => ({
                                    items: d.items,
                                    header: { ...d.header, discount: a },
                                }))
                            }
                            setNote={(a: string) =>
                                setData((d) => ({
                                    items: d.items,
                                    header: { ...d.header, note: a },
                                }))
                            }
                            setAddition={(a: number) =>
                                setData((d) => ({
                                    items: d.items,
                                    header: { ...d.header, addition: a },
                                }))
                            }
                            goNext={() => {
                                saveButtonRef.current?.focus();
                            }}
                        />

                        <div className="flex gap-2 p-3">
                            <Button ref={saveButtonRef} onClick={onSubmit}>
                                {__('save')}
                            </Button>
                            {path=='update'?(

                            <Button
                                variant={'outline'}
                                 onClick={(e)=>{
                                     e.preventDefault()
                                     router.get(route('sales.create'))
                                 }}>
                                {__('new')}
                            </Button>
                            ):(
                                <Button
                                    variant={'outline'}
                                    onClick={(e)=>{
                                        e.preventDefault()
                                        post(route('sales.store'),{
                                            onSuccess:()=>{
                                                router.get(route('sales.create'))
                                            }
                                        })
                                        // router.get(route('sales.create'))
                                    }}>
                                    {__('save and new')}
                                </Button>
                            )}
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
    updateRowUnit,
    updateRowQuantity,
}: {
    saleTotal: number;
    saleUnitTotal: number;

    items: SaleItem[];
    getUnit: (item: SaleItem) => Unit | undefined;
    onItemDelete: (index: number) => void;
    updateRowUnit: (v: string, i: number) => void;
    updateRowQuantity: (by: -1 | 1, i: number) => void;
}) {
    const {__}=useLang()
    return (
        <div>
            <div className="h-10"></div>
            <div className={'rounded-md border p-1'}>
                <Table>
                    {/*<TableCaption>A list of your recent invoices.</TableCaption>*/}
                    <TableHeader>
                        <TableRow>
                            <TableHead className="w-[100px]">{__('product')}</TableHead>
                            <TableHead className={'text-center'}>{__('quantity')}</TableHead>
                            <TableHead className={'text-center'}>{__('unit')}</TableHead>
                            <TableHead className={'text-center'}>{__('unit price')}</TableHead>
                            <TableHead className={'text-center'}>{__('total price')}</TableHead>
                            <TableHead className="pe-5 text-end">...</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {items.map((item, index) => (
                            <TableRow key={`data-item-${item.product_id}-${index}`}>
                                <TableCell className="font-medium">{item.product.name_ar}</TableCell>
                                <TableCell>
                                    <div className={'flex items-center justify-center gap-1'}>
                                        <Plus
                                            onClick={() => updateRowQuantity(1, index)}
                                            size={20}
                                            className={'rounded border hover:cursor-pointer'}
                                        />
                                        <div className={'px-1 select-none'}>{item.quantity}</div>
                                        <Minus
                                            onClick={() => updateRowQuantity(-1, index)}
                                            size={20}
                                            className={'rounded border hover:cursor-pointer'}
                                        />
                                    </div>
                                </TableCell>

                                <TableCell className={'flex justify-center'}>
                                    <div>
                                        {item.product.units.length > 1 ? (
                                            <Select dir={'rtl'} value={item.unit_id.toString()} onValueChange={(v) => updateRowUnit(v, index)}>
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
            </div>

            <div className="h-5"></div>
        </div>
    );
}

function Details({
    header,
    saleTotal,
    setDiscount,
    setNote,
    setAddition,
    goNext
}: {
    saleTotal: number;
    header: SaleHeader;
    setDiscount: (a: number) => void;
    setAddition: (a: number) => void;
    setNote: (a: string) => void;
    goNext:()=>void
}) {
    const discount = header.discount;
    const addition = header.addition;
    const note = header.note;

    const discountRef = useRef<HTMLInputElement>(null)
    const additionRef = useRef<HTMLInputElement>(null)
    const noteRef = useRef<HTMLInputElement>(null)
    const {__}=useLang()
    return (
        <form>
            <div className="flex gap-3">
                <div className="">
                    <Label>{__('discount')}</Label>
                    <Input
                        ref={discountRef}
                        onKeyDown={(k) => {
                            if (k.key == 'Enter') {
                                additionRef.current?.focus();
                            }
                            console.log('ssssssssssssss');
                        }}
                        defaultValue={discount}
                        type={'number'}
                        onChange={(e) => setDiscount(Number(e.target.value))}
                    />
                </div>
                <div className="">
                    <Label>{__('addition')}</Label>
                    <Input
                        ref={additionRef}
                        onKeyDown={(k) => {
                            if (k.key == 'Enter') {
                                noteRef.current?.focus();
                            }
                        }}
                        defaultValue={addition}
                        type={'number'}
                        onChange={(e) => setAddition(Number(e.target.value))}
                    />
                </div>
                <div className="flex-1">
                    <Label>{__('note')}</Label>
                    <Input
                        defaultValue={note}
                        onKeyDown={(k) => {
                            if (k.key == 'Enter') {
                                goNext();
                            }
                        }}
                        ref={noteRef}
                        className={'overflow-x-auto border'}
                        onChange={(e) => setNote(e.target.value)}
                    />
                </div>
            </div>
            <div className="h-1"></div>

            <div className="flex justify-between border">
                <div className="">
                    <div className="flex gap-2 p-1">
                        <div className="">{__('cost price')} :</div>
                        <div className="">{saleTotal}</div>
                    </div>
                    <div className="flex gap-2 p-1">
                        <div className="">{__('discount')} :</div>
                        <div className="">{discount}</div>
                    </div>
                    <div className="flex gap-2 p-1">
                        <div className="">{__('total price')} :</div>
                        <div className="">{saleTotal - discount}</div>
                    </div>
                </div>
            </div>
        </form>
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

{
    /*<div className="relative">*/
}
{
    /*    <input*/
}
{
    /*        onKeyDown={onKeyDown}*/
}
{
    /*        key={'a2'}*/
}
{
    /*        autoFocus*/
}
{
    /*        ref={inputRef}*/
}
{
    /*        value={search}*/
}
{
    /*        onFocus={() => setShow(true)}*/
}
{
    /*        onChange={(e) => {*/
}
{
    /*            setSearch(e.target.value);*/
}
{
    /*        }}*/
}
{
    /*        className={'z-20 w-[400px] rounded-lg border p-1'}*/
}
{
    /*        onClick={(e) => {*/
}
{
    /*            e.stopPropagation();*/
}
{
    /*            e.preventDefault();*/
}
{
    /*            setShow(true);*/
}
{
    /*        }}*/
}
{
    /*    />*/
}

{
    /*    {showMenu && (*/
}
{
    /*        <div ref={menuRef} className={'absolute z-20 max-h-[250px] w-full overflow-y-auto bg-background'}>*/
}
{
    /*            {products.map((p) => (*/
}
{
    /*                <div*/
}
{
    /*                    onClick={() => onProductOptionSelected(p)}*/
}
{
    /*                    title={p.name_ar}*/
}
{
    /*                    key={`op-${p.id}`}*/
}
{
    /*                    className={cn(*/
}
{
    /*                        'mb-[1px] w-full border p-2 hover:cursor-pointer hover:bg-accent',*/
}
{
    /*                        product?.id == p.id ? 'bg-gray-300' : '',*/
}
{
    /*                    )}*/
}
{
    /*                >*/
}
{
    /*                    {p.name_ar}*/
}
{
    /*                </div>*/
}
{
    /*            ))}*/
}
{
    /*        </div>*/
}
{
    /*    )}*/
}
{
    /*</div>*/
}
