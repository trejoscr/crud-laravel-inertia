<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = ['id_brand', 'name', 'price'];

  public function scopeFilter($query, array $filters)
  {
    $query->when($filters['search'] ?? null, function ($query, $search) {
      $query->where('name', 'like', '%'.$search.'%');
    });
  }

  public function brands(){
    //belongsTo: un producto pertence e una categoria
    return $this->belongsTo(Brand::class, 'id_brand');
  }

}
