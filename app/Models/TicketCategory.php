<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCategory extends Model
{
    //


    /**
     * Get the project that owns the bug.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
