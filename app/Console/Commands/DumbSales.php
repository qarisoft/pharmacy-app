<?php

namespace App\Console\Commands;

use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\Models\Refund\ReturnHeader;
use App\Models\Refund\ReturnItem;
use App\Models\Refund\WithDraw;
use App\Models\Sales\SaleHeader;
use App\Models\Sales\SaleItem;
use App\Models\Store\ProductInput;
use App\Models\Store\ProductInputHeader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DumbSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    protected function sr($val): string
    {

        return str_replace("'", '\\\'', $val,);
    }

    protected function mk($name, $val): string
    {
        return  "'" . $name . "'" . "=>" . "'" . $this->sr($val) . "'" . ',';
    }
        protected function mkId($name, $val): string
    {
        return  "'" . $name . "'" . "=>" . (intval($val) ? $val : "null") . ',';
    }
    protected function mkIdZero($name, $val): string
    {
        return  "'" . $name . "'" . "=>" . (intval($val) ? $val : 0) . ',';
    }
    protected function _close(): string
    {
        return '],
';
    }
    protected function makeFile(string $fileName,string $data)
    {

        $a = '
<?php

return [
';
        $a=$a.$data;

        $a = $a . '
];';
        file_put_contents($fileName, $a);
    }





    function saleHeaders() : void {
        $a0='';
        foreach (SaleHeader::all() as $p) {

            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkId('end_price', $p->end_price)
                . $this->mkId('cost_price', $p->cost_price)
                . $this->mkIdZero('discount', $p->discount)
                . $this->mk('customer_name', $p->customer_name)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/sales/sale_headers.php',$a0);
    }
    function saleItems() : void {
        $a0='';
        foreach (SaleItem::all() as $p) {
            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkId('product_id', $p->product_id)
                . $this->mkId('header_id', $p->header_id)
                . $this->mkId('quantity', $p->quantity)
                . $this->mkId('end_price', $p->end_price)
                . $this->mkId('cost_price', $p->cost_price)
                . $this->mkId('unit_cost_price', $p->unit_cost_price)
                . $this->mkId('product_price', $p->product_price)
                . $this->mkIdZero('discount', $p->discount)
                . $this->mkIdZero('unit_id', $p->unit_id)
                . $this->mkIdZero('unit_count', $p->unit_count)
                . $this->mk('profit', $p->profit)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/sales/sale_items.php',$a0);
    }

    public function makeSaleItems(SaleHeader $p) : string
    {
        $a='
    [';
        foreach ($p->items as $item) {
            $a0 = $this->makeSaleItem($item);
            $a = $a . $a0;
        }
        $a=$a.'
    ]';
        return  $a;
    }
    public function makeSaleItem($p)
    {
        $a1 = '['
//            . $this->mkId('id', $p->id)
            . $this->mkId('product_id', $p->product_id)
            . $this->mkId('header_id', $p->header_id)
            . $this->mkId('quantity', $p->quantity)
            . $this->mkId('cost_price', $p->cost_price)
            . $this->mkId('end_price', $p->end_price)
            . $this->mkId('product_price', $p->product_price)
            . $this->mkIdZero('discount', $p->discount)
            . $this->mkId('unit_id', $p->getUnitId())
            . $this->mkId('unit_count', $p->type)
            . $this->mk('created_at', $p->created_at)
            . $this->mk('updated_at', $p->updated_at)
            . $this->_close();
        return $a1;
    }



    public function returnHeaders()
    {
        $a0='';
        foreach (ReturnHeader::all() as $p) {
            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkIdZero('end_price', $p->end_price)
                . $this->mkIdZero('cost_price', $p->cost_price)
                . $this->mkIdZero('discount', $p->discount)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/sales/return_headers.php',$a0);

    }
    public function returnItems()
    {
        $a0='';
        foreach (ReturnItem::all() as $p) {

            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkId('product_id', $p->product_id)
                . $this->mkId('header_id', $p->header_id)
                . $this->mkIdZero('end_price', $p->end_price)
                . $this->mkIdZero('product_price', $p->product_price)
//                . $this->mkIdZero('discount', $p->discount)
                . $this->mkIdZero('quantity', $p->quantity)
                . $this->mkIdZero('unit_id', $p->unit_id)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/sales/return_items.php',$a0);
    }

    public function withDrows()
    {

        $a0='';
        foreach (WithDraw::all() as $p) {

            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mk('note', $p->note)
                . $this->mkIdZero('amount', $p->amount)
                . $this->mkId('created_by', $p->created_by)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/sales/with_draws.php',$a0);
    }

    public function handle()
    {
        // $sales = DB::connection('sqlite')->table('sale_headers')->get();
        $this->saleHeaders();
        $this->returnHeaders();
        $this->returnItems();
        $this->withDrows();
        $this->saleItems();
    }
}
