<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugCommentFile extends Model
{
    protected $fillable = ['bug_comment_id', 'file_path'];

    public function bugComment(): BelongsTo
    {
        return $this->belongsTo(BugComment::class);
    }
}
