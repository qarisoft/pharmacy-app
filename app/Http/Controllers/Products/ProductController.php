<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected string $path='products';
    public function index()
    {
        $data = $this->paginate(Product::query());
        return Inertia::render($this->path . '/index', [
            'pageData' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render($this->path . '/create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
        ]);
        $p=Product::query()->create([
            'name_ar'=>$request->name_ar,
            'name_en'=>$request->name_en,
            'barcode'=>$request->barcode,
        ]);

        return Redirect::route('products.edit',$p->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render($this->path . '/show', [
           'product' => Product::query()->find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render($this->path . '/edit', [
            'product' => Product::query()->find($id)
        ]);
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
