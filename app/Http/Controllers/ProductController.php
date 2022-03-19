<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $sortby = $request->sortby ? $request->sortby : 'id';
    $direction = $request->direction ? $request->direction : 'asc';

    return Inertia::render('products/index', [
      'filters' => $request->all('search'),
      'products' => Product::orderBy($sortby, $direction)
      ->filter($request->only('search'))
      ->paginate(5)
      ->withQueryString()
      ->through(fn ($product) => [
        'id'          => $product->id,
        'id_brand'    => $product->id_brand,
        'name'        => $product->name,
        'price'       => $product->price,
        'brand'       => $product->brands->name,
      ]),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return Inertia::render('products/create', [
      'brands' => Brand::all()
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'id_brand' => 'required',
      'name'     => 'required',
      'price'    => 'required|numeric'
    ],
    [
      'id_brand.required' => 'The brand field is required.',
    ]);

    Product::create($request->all());
    return Redirect::route('products.index')->with('message', 'Product Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Producto  $product
   * @return \Illuminate\Http\Response
   */
  public function show(Product $product)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Producto  $product
   * @return \Illuminate\Http\Response
   */
  public function edit(Product $product)
  {
    return Inertia::render('products/edit', [
      'product' => $product,
      'brands'  => Brand::all()
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Producto  $product
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Product $product)
  {
    $request->validate([
      'name'     => 'required',
      'id_brand' => 'required',
      'price'    => 'required|numeric'
    ],
    [
      'id_brand.required' => 'The brand field is required.',
    ]);

    $product->update($request->all());
    return Redirect::route('products.index')->with('message', 'Product Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Producto  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    $product->delete();
    return Redirect::route('products.index')->with('message', 'Product Deleted');
  }
}
