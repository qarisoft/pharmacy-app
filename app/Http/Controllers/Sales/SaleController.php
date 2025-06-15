<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\Models\Sales\SaleHeader;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return inertia('sales/index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('sales/create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data= [
            'discount'=>$request->header['discount'],
            'addition'=>$request->header['addition'],
            'customer_name'=>$request->header['customer_name'],
            'note'=>$request->header['note'],
        ];
//        dd($data);
        $header = SaleHeader::factory()->create($data);
//        dd($request->items);
//        $table->foreignId('product_id');
//        $table->foreignId('header_id')->nullable();
//        $table->integer('quantity');
//        $table->double('end_price');
//        $table->double('cost_price')->nullable();
//        $table->double('unit_cost_price')->nullable();
//        $table->double('product_price')->nullable();
//        $table->double('discount')->nullable();
//        $table->double('profit')->default(0);
//        $table->integer('unit_id')->default(1);
//        $table->integer('unit_count')->nullable();

        foreach ($request->items as $item){
            $unit=MeasureUnit::find($item['unit_id']);
            $product=Product::find($item['product_id']);
            $header->items()->create([
                'product_id'=>$item['product_id'],
                'quantity'=>$item['quantity'],
                'end_price'=>number_format($unit->count*$item['quantity']*$product->unit_price,1),
                'product_price'=>$product->unit_price,
                'unit_id'=>$item['unit_id'],
                'unit_count'=>$unit->count,
            ]);

        }
        $this->success('done');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
