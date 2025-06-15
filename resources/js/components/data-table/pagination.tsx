// import {
//     Pagination,
//     PaginationContent,
//     PaginationEllipsis,
//     PaginationItem,
//     PaginationLink,
//     PaginationNext,
//     PaginationPrevious,
// } from "@/components/ui/pagination"
// import { PaginatedData } from '@/types';
// import { router, usePage } from '@inertiajs/react';
// import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
//
// export default function TablePagination<TData>({pagination}:{pagination:PaginatedData<TData>}) {
//     const { url } = usePage()
//
//     const paginate = (perPage: string) => {
//         router.get(url, {
//             perPage,
//         })
//     }
//     return (
//
//        <div className={'flex'}>
//
//            <Pagination>
//                <PaginationContent>
//                    <PaginationItem>
//                        <PaginationPrevious href="#" />
//                    </PaginationItem>
//                    <PaginationItem>
//                        <PaginationLink href="#">1</PaginationLink>
//                    </PaginationItem>
//                    <PaginationItem>
//                        <PaginationLink href="#" isActive>
//                            2
//                        </PaginationLink>
//                    </PaginationItem>
//                    <PaginationItem>
//                        <PaginationLink href="#">3</PaginationLink>
//                    </PaginationItem>
//                    <PaginationItem>
//                        <PaginationEllipsis />
//                    </PaginationItem>
//
//                    <PaginationItem>
//                        <PaginationNext href="#" />
//                    </PaginationItem>
//                </PaginationContent>
//            </Pagination>
//            <PerPage pagination={pagination}/>
//        </div>
//     )
// }
// function PerPage<TData>({ pagination }: { pagination: PaginatedData<TData> }) {
//     // const { t } = useLang()
//
//     const { url } = usePage()
//
//     const paginate = (perPage: string) => {
//         router.get(url, {
//             perPage,
//         })
//     }
//     return (
//         <div className="flex items-center space-x-2 flex-1">
//             <p className="text-sm font-medium">{('Rows per page')}</p>
//             <Select
//                 value={`${pagination.per_page}`}
//                 onValueChange={paginate}
//             >
//                 <SelectTrigger className="h-8 w-[70px]">
//                     <SelectValue placeholder={pagination.per_page} />
//                 </SelectTrigger>
//                 <SelectContent side="top">
//                     {[10, 15, 20, 30, 40, 50].map((pageSize) => (
//                         <SelectItem key={pageSize} value={`${pageSize}`}>
//                             {pageSize}
//                         </SelectItem>
//                     ))}
//                 </SelectContent>
//             </Select>
//         </div>
//     )
// }
