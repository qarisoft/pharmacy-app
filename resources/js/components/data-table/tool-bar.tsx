import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { ToolBarOptions } from '@/types';
import { router, usePage } from '@inertiajs/react';
import { type Table } from '@tanstack/react-table';
import { ChevronDown, Plus } from 'lucide-react';
interface DataTableToolbarProps<TData> {
    table: Table<TData>;
    options?: ToolBarOptions;
}

export default function TableToolBar<TData>({ table, options }: DataTableToolbarProps<TData>) {
    const createFunc = () => {
        if (options && options.onCreate) {
            return options.onCreate();
        }
        router.get(window.location.pathname + '/create');
    };
    const { url, props } = usePage<{ search: string }>();

    const search = (searchKey: string) => {
        router.get(url, {
            search: searchKey
        }, {
            preserveState: true,
            showProgress: false
        });
    };
    return (
        <div className="flex items-center justify-between p-4">
            <Input
                placeholder="Filter tasks..."
                defaultValue={route().queryParams['search'] as string}
                onChange={(e) => search(e.target.value)}
                className="h-8 pb-1 w-[150px] lg:w-[250px]"
            />
            <div className="flex gap-2">
                <Button onClick={createFunc} variant={'outline'}>
                    <Plus />
                </Button>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="outline" className="ms-auto">
                            Columns <ChevronDown />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        {table
                            .getAllColumns()
                            .filter((column) => column.getCanHide())
                            .map((column) => {
                                return (
                                    <DropdownMenuCheckboxItem
                                        key={column.id}
                                        className="capitalize"
                                        checked={column.getIsVisible()}
                                        onCheckedChange={(value) => column.toggleVisibility(!!value)}
                                    >
                                        {column.id}
                                    </DropdownMenuCheckboxItem>
                                );
                            })}
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>
    );
}
