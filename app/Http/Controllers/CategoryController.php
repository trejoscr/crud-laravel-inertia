<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
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

    return Inertia::render('categories/index', [
      'filters' => $request->all('search'),
      'categories' => Category::orderBy($sortby, $direction)
      ->filter($request->only('search'))
      ->paginate(5)
      ->withQueryString()
      ->through(fn ($category) => [
        'id'          => $category->id,
        'name'        => $category->name,
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
    return Inertia::render('categories/create');
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
      'name' => 'required',
    ]);

    Category::create($request->all());
    return Redirect::route('categories.index')->with('message', 'Category Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function show(Category $category)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function edit(Category $category)
  {
    return Inertia::render('categories/edit', ['category'=>$category]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Category $category)
  {
    $request->validate([
      'name' => 'required',
    ]);

    $category->update($request->all());
    return Redirect::route('categories.index')->with('message', 'Category Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function destroy(Category $category)
  {
    $category->delete();
    return Redirect::route('categories.index')->with('message', 'Category Deleted');
  }
}
