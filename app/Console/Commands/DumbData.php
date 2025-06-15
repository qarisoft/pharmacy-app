<?php

namespace App\Console\Commands;

use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\Models\Sales\SaleHeader;
use App\Models\Sales\SaleItem;
use App\Models\Store\ProductInput;
use App\Models\Store\ProductInputHeader;
use Illuminate\Console\Command;

class DumbData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dumb-data';

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

        return str_replace("'", '\\\'', $val);
    }

    protected function mk($name, $val): string
    {
        return "'" . $name . "'" . "=>" . "'" . $this->sr($val) . "'" . ',';
    }

    protected function mkN($name, $val): string
    {
        return "'" . $name . "'" . "=>" .( $val ? ("'" . $this->sr($val) . "'") : 'null') . ',';
    }

    protected function mkId($name, $val): string
    {
        return "'" . $name . "'" . "=>" . (intval($val) ? $val : "null") . ',';
    }

    protected function mkIdZero($name, $val): string
    {
        return "'" . $name . "'" . "=>" . (intval($val) ? $val : 0) . ',';
    }

    protected function _close(): string
    {
        return '],
';
    }

    protected function makeFile(string $fileName, string $data)
    {

        $a = '
<?php

return [
';
        $a = $a . $data;

        $a = $a . '
];';
        file_put_contents($fileName, $a);
    }


    function products(): void
    {
        $a0 = '';


        foreach (Product::all() as $p) {
            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkIdZero('unit_price', $p->unit_price)
                . $this->mk('barcode', $p->barcode)
                . $this->mk('name_ar', $p->name_ar)
                . $this->mk('name_en', $p->name_en)
                . $this->mk('scientific_name', $p->scientific_name)
                . $this->mkId('company_id', $p->company_id)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/products.php', $a0);

    }

    function productInputs(): void
    {
        $a0 = '';

        foreach (ProductInput::all() as $p) {
            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkId('quantity', $p->quantity)
                . $this->mkId('product_id', $p->product_id)
                . $this->mkId('header_id', $p->header_id)
                . $this->mkId('vendor_id', $p->vendor_id)
                . $this->mk('payment_type', $p->payment_type)
                . $this->mkId('total_cost_price', $p->total_cost_price)
                . $this->mkId('unit_cost_price', $p->unit_cost_price)
                . $this->mkN('expire_date', $p->expire_date)
                . $this->mkId('unit_id', $p->unit_id)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }

        $this->makeFile('database/seeders/data/product_inputs.php', $a0);
    }

    function productInputHeaders(): void
    {
        $a0 = '';
        foreach (ProductInputHeader::all() as $p) {
            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mkIdZero('total_price', $p->total_price)
                . $this->mkId('bill_number', $p->bill_number)
                . $this->mkId('vendor_id', $p->vendor_id)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }

        $this->makeFile('database/seeders/data/product_input_headers.php', $a0);
    }

    function measureUnits(): void
    {
        $a0 = '';
        foreach (MeasureUnit::all() as $p) {
            $a1 = '['
                . $this->mkId('id', $p->id)
                . $this->mk('name', $p->name)
                . $this->mkId('product_id', $p->product_id)
                . $this->mkIdZero('discount', $p->discount)
                . $this->mkIdZero('sell_price', $p->sell_price)
                . $this->mkIdZero('cost_price', $p->cost_price)
                . $this->mkId('count', $p->count)
                . $this->mk('created_at', $p->created_at)
                . $this->mk('updated_at', $p->updated_at)
                . $this->_close();
            $a0 = $a0 . $a1;
        }
        $this->makeFile('database/seeders/data/measure_units.php', $a0);
    }

    public function handle()
    {
        $this->products();
        $this->measureUnits();
        $this->productInputHeaders();
        $this->productInputs();
    }
}
