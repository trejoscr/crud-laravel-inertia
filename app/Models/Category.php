<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  protected $fillable = ['name'];

  public function scopeFilter($query, array $filters)
  {
    $query->when($filters['search'] ?? null, function ($query, $search) {
      $query->where('name', 'like', '%'.$search.'%');
    });
  }

  public function products(){
    //belongsToMany: una categoria pertenece a muchos productos
    return $this->belongsToMany(Product::class, 'product_category');
  }
  
}
