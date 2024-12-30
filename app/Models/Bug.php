<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
class Bug extends Model
{
    //

    /**
     * Get the post that owns the comment.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeFilterByStatus(Builder $query, Request $request): Builder
    {
        if ($request->has(['status'])) {
            return $query->whereIn('status', $request->get('status'));
        }
        // Applique le filtre sur le statut
        return $query->where('status', '!=', 6);
    }
}
