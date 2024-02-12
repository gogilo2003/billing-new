<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use HasFactory;

    /**
     * Get the client that owns the Domain.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
