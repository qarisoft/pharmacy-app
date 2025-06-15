import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { ToolBarOptions } from '@/types';
import { router } from '@inertiajs/react';
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
    return (
        <div className="flex items-center justify-between p-4">
            <Input
                placeholder="Filter emails..."
                // value={(table.getColumn("email")?.getFilterValue() as string) ?? ""}
                // onChange={(event) =>
                //     table.getColumn("email")?.setFilterValue(event.target.value)
                // }
                className="max-w-sm"
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
