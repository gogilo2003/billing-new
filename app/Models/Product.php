<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
