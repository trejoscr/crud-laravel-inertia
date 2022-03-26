<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
  
  public function index(){
    $product = Product::find(2);
    $category = Category::find(2);  

    return Inertia::render('product_category/index', [
      'product' => $product,
      'category' => $category,
    ]);

  }

}
