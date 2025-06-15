import { Table } from '@tanstack/react-table';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useLang } from '@/hooks/use-lang';
import { PaginatedData } from '@/types';
import { router, usePage } from '@inertiajs/react';

interface DataTablePaginationProps<TData> {
    table: Table<TData>;
    pagination: PaginatedData<TData>;
}

export default function DataTablePagination<TData>({ table, pagination }: DataTablePaginationProps<TData>) {
    return (
        <div className="flex w-full items-center justify-between space-x-6 p-2 lg:space-x-8">
            <A pagination={pagination} />
            <PerPage pagination={pagination} />
            <div className="text-sm text-muted-foreground">
                {table.getFilteredSelectedRowModel().rows.length} of {table.getFilteredRowModel().rows.length} row(s) selected.
            </div>
        </div>
    );
}

function PerPage<TData>({ pagination }: { pagination: PaginatedData<TData> }) {
    const { t } = useLang();

    const { url } = usePage();

    const paginate = (perPage: string) => {
        router.get(url, {
            perPage,
        });
    };
    return (
        <div className="flex items-center space-x-2">
            <p className="text-sm font-medium">{t('Rows per page')}</p>
            <Select value={`${pagination.per_page}`} onValueChange={paginate}>
                <SelectTrigger className="h-8 w-[70px]">
                    <SelectValue placeholder={pagination.per_page} />
                </SelectTrigger>
                <SelectContent side="top">
                    {[10, 15, 20, 30, 40, 50].map((pageSize) => (
                        <SelectItem key={pageSize} value={`${pageSize}`}>
                            {pageSize}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </div>
    );
}

function A<TData>({ pagination }: { pagination: PaginatedData<TData> }) {
    const { url } = usePage();
    const goToPage = (page: number) => {
        router.get(url, {
            page,
        });
    };
    const goToNextPage = () => goToPage(pagination.current_page + 1);
    const goToPreviousPage = () => goToPage(pagination.current_page - 1);
    const goToLastPage = () => goToPage(pagination.last_page);
    const goToFirstPage = () => goToPage(1);

    return (
        <div className="flex">
            <div dir="ltr" className="flex w-[100px] items-center justify-center gap-1 text-sm font-medium">
                <span>{pagination.current_page} </span>
                <span>of </span>
                <span>{pagination.last_page} </span>
            </div>

            <div dir="ltr" className="flex items-center space-x-2">
                <Button variant="outline" className="hidden h-8 w-8 p-0 lg:flex" onClick={goToFirstPage} disabled={pagination.current_page == 1}>
                    <span className="sr-only">Go to first page</span>
                    <ChevronsLeft />
                </Button>

                <Button variant="outline" className="h-8 w-8 p-0" onClick={goToPreviousPage} disabled={pagination.prev_page_url == null}>
                    <span className="sr-only">Go to previous page</span>
                    <ChevronLeft />
                </Button>

                <Button variant="outline" className="h-8 w-8 p-0" onClick={goToNextPage} disabled={pagination.next_page_url == null}>
                    <span className="sr-only">Go to next page</span>
                    <ChevronRight />
                </Button>

                <Button
                    variant="outline"
                    className="hidden h-8 w-8 p-0 lg:flex"
                    onClick={goToLastPage}
                    disabled={pagination.current_page == pagination.last_page}
                >
                    <span className="sr-only">Go to last page</span>
                    <ChevronsRight />
                </Button>
            </div>
        </div>
    );
}
