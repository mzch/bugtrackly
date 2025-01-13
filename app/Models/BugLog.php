<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugLog extends Model
{
    protected $fillable = ['bug_id', 'user_id', 'action', 'details'];
    protected $with = [
        'user',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
