<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BugComment extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user',
        'files'
    ];

    protected $fillable = [
      'content'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (BugComment $bugComment) {
            $bugComment->user_id = Auth::id();
        });

        // Lors de la création d'un bug, on met à jour updated_at du projet
        static::created(function (BugComment $bugComment) {
            $bugComment->bug->project->touch();
        });
        static::updated(function (BugComment $bugComment) {
            $bugComment->bug->touch();
            $bugComment->bug->project->touch();
        });

        static::deleting(function (BugComment $bugComment) {
            $directory = "bug_comments/{$bugComment->id}";
            if (Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        });


    }


    public function bug(): BelongsTo
    {
        return $this->belongsTo(Bug::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files():HasMany
    {
        return $this->hasMany(BugCommentFile::class);
    }
}
