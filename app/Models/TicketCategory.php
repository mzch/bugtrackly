<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCategory extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'order',
    ];


    /**
     * Get the project that owns the bug.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
