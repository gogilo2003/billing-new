<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $casts = ['delivery_date' => 'datetime'];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
