<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class BrandController extends Controller
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

    return Inertia::render('brands/index', [
      'filters' => $request->all('search'),
      'brands' => Brand::orderBy($sortby, $direction)
      ->filter($request->only('search'))
      ->paginate(5)
      ->withQueryString()
      ->through(fn ($brand) => [
        'id'          => $brand->id,
        'name'        => $brand->name,
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
    return Inertia::render('brands/create');
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

    Brand::create($request->all());
    return Redirect::route('brands.index')->with('message', 'Brand Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Brand  $brand
   * @return \Illuminate\Http\Response
   */
  public function show(Brand $brand)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Brand  $brand
   * @return \Illuminate\Http\Response
   */
  public function edit(Brand $brand)
  {
    return Inertia::render('brands/edit', ['brand'=>$brand]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Brand  $brand
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Brand $brand)
  {
    $request->validate([
      'name' => 'required',
    ]);

    $brand->update($request->all());
    return Redirect::route('brands.index')->with('message', 'Brand Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Brand  $brand
   * @return \Illuminate\Http\Response
   */
  public function destroy(Brand $brand)
  {
    $brand->delete();
    return Redirect::route('brands.index')->with('message', 'Brand Deleted');
  }
}
