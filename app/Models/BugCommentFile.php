<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugCommentFile extends Model
{
    protected $fillable = [
        'file_path',
        'size',
        'size_human_readable'
    ];


    public function bugComment(): BelongsTo
    {
        return $this->belongsTo(BugComment::class);
    }
}
