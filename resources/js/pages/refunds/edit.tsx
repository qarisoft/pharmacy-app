import SaleForm from '@/pages/sales/page';
import { SaleHeader, SaleItem } from '@/types';

export default function ({ row }: { row: SaleHeader & {id:number, items: SaleItem[] } }) {
    console.log(row);
    return (
        <div>
            <SaleForm autofocus={false} items={row.items} header={row} path={'update'} header_id={row.id}  />
        </div>
    );
}
