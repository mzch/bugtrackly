<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Bug extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'priority',
        'status',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user'
    ];

    protected $appends = [
      'bug_id_formatted'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Bug $bug) {
            $bug->user_id = Auth::id();
            $bug->status = 1;
        });

        // Lors de la création d'un bug, on met à jour updated_at du projet
        static::created(function (Bug $bug) {
            $bug->project->touch();
        });

        static::updated(function (Bug $bug) {
            $bug->project->touch();
        });
    }

    /**
     * Get the post that owns the comment.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bug_comments() : HasMany
    {
        return $this->hasMany(BugComment::class)->orderBy('created_at');
    }

    public function scopeBugSearch(Builder $query, Request $request)
    {
        if ($request->has('search')) {
            $searchValue = strtolower(StringHelper::remove_accents($request->get('search')));

            if (is_numeric($searchValue)) {
                // Recherche sur l'id ou le title
                $query->where(function ($query) use ($searchValue) {
                    $query->where('id', $searchValue)
                        ->orWhere('title', 'like', '%' . $searchValue . '%');
                });
            } else {
                $query->where('title', 'like', '%' . $searchValue . '%');
            }
        }
        return $query;
    }

    /**
     * Scope pour filtrer sur les status des bugs
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeFilterByStatus(Builder $query, Request $request): Builder
    {
        if ($request->has(['status'])) {
            $request_status = $request->get('status');
            switch ($request_status) {
                case 'new' :
                    return $query->where('status', 1);
                case 'accepted' :
                    return $query->where('status', 2);
                case 'rejected' :
                    return $query->where('status', 3);
                case 'in_progress' :
                    return $query->where('status', 4);
                case 'resolved' :
                    return $query->where('status', 5);
                case 'closed' :
                    return $query->where('status', 6);
                case 'reopened' :
                    return $query->where('status', 7);
                default :
                    return $query->where('status', '!=', 6);
            }
        }
        return $query->where('status', '!=', 6);
    }

    /**
     * Scope pour filtrer sur les prioritées des bugs
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeFilterByPriority(Builder $query, Request $request): Builder
    {
        if ($request->has(['priority'])) {
            $request_priority = $request->get('priority');
            switch ($request_priority) {
                case 'none':
                    return $query->where('priority', 1);
                case 'low':
                    return $query->where('priority', 2);
                case 'normal':
                    return $query->where('priority', 3);
                case 'hight':
                    return $query->where('priority', 4);
                case 'immediate':
                    return $query->where('priority', 5);
            }
            return $query->where('priority', $request->get('priority'));
        }
        return $query;
    }

    /**
     * Scope pour ordonner les bugs
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeBugOrderBy(Builder $query, Request $request): Builder
    {
        if ($request->has(['field', 'direction'])) {
            switch ($request->field) {
                case "date" :
                    $sortField = 'updated_at';
                    break;
                default :
                    $sortField = $request->field;
            }
            if ($request->direction === 'asc') {
                $query->orderBy($sortField);
            } else {
                $query->orderByDesc($sortField);
            }
        } else {
            $query->orderBy('updated_at', 'desc');
        }
        return $query;
    }

    protected function bugIdFormatted(): Attribute{


        return new Attribute(
            get: fn () => str_pad($this->id, 7, '0', STR_PAD_LEFT)
        );
    }
}
