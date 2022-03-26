<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductCategory;
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
        'categories'  => $product->categories,
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
      'brands'     => Brand::all(),
      'categories' => Category::all(),
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

    $product = Product::create($request->all());
    $categories = $request->input('id_categories');

    if (!empty($categories)) {
      foreach ($categories as $key => $value) {
        ProductCategory::create([
          'product_id'  => $product->id,
          'category_id' => $value,
        ]);
      }
    }
    

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

    $product_categories = array();
    foreach ($product->categories as $key => $value) {
      $product_categories[] = $value->id;
    }

    return Inertia::render('products/edit', [
      'product'            => $product,
      'product_categories' => $product_categories,
      'brands'             => Brand::all(),
      'categories'         => Category::all(),
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


    $old_categories = array();
    foreach ($product->categories as $key => $value) {
      $old_categories[] = $value->id;
    }

    $categories = $request->input('product_categories');
    $new_categories = array();

    foreach ($categories as $key => $value) {
      $new_categories[] = $value;
    }

    // search wich product categories in table pivot
    $categories_to_delete=array_diff($old_categories,$new_categories);

    // remove old product categories in table pivot
    if (!empty($categories_to_delete)) {
      foreach ($categories_to_delete as $key => $value) {
        ProductCategory::where('product_id', $product->id)->where('category_id', $value)->delete();
      }
    }

    // search wich product save in table pivot
    $categories_to_save=array_diff($new_categories, $old_categories);

    // save new product categories in table pivot
    if (!empty($categories_to_save)) {
      foreach ($categories_to_save as $key => $value) {
        ProductCategory::create([
          'product_id'  => $product->id,
          'category_id' => $value,
        ]);
      }
    }

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
