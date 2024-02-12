<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProvisionItem extends Model
{
    use HasFactory;
    /**
     * Get the provision that owns the ProvisionItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provision(): BelongsTo
    {
        return $this->belongsTo(Provision::class);
    }
}
