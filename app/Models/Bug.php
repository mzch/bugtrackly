<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class Bug extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'priority',
        'status',
        //  'user_id', //à delete et tester le seeder....
        'assigned_user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user',
        'assigned_user',
        'ticket_category'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'bug_id_formatted'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status'   => 'integer',
        'priority' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Bug $bug) {
            if (empty($bug->user_id)) {
                $bug->user_id = Auth::id();
            }
            if (empty($bug->status)) {
                $bug->status = 1;
            }
        });

        // Lors de la création d'un bug, on met à jour "updated_at" du projet
        static::created(function (Bug $bug) {
            $bug->project->touch();
        });

        static::updated(function (Bug $bug) {
            $bug->project->touch();
        });


        // Suppression de tous les fichiers liés au bug
        static::deleting(function ($bug) {
            foreach ($bug->bug_comments as $comment) {
                $directory = "bug_comments/{$comment->id}";
                if (Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->deleteDirectory($directory);
                }
            }
        });
    }

    /**
     * Get the project that owns the bug.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function ticket_category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assigned_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function user_followers()
    {
        return $this->belongsToMany(User::class, 'followed_bug_user');
    }

    public function bug_comments(): HasMany
    {
        return $this->hasMany(BugComment::class)->orderBy('created_at');
    }

    public function bug_logs(): HasMany
    {
        return $this->hasMany(BugLog::class)->orderBy('created_at');
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
                case 'all' :
                    return $query;
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

    public function scopeBugOpened(Builder $query): Builder
    {
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

    public function scopeFilterByProjectSlug(Builder $query, Request $request): Builder
    {
        if ($request->has(['project'])) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('slug', $request->get('project'));
            });
        }
        return $query;
    }

    public function scopeFilterByCategory(Builder $query, Request $request): Builder
    {
        if ($request->has(['category'])) {
            $request_category = $request->get('category');
            if($request_category === "none"){
                return $query->whereNull('ticket_category_id');
            }else{

                $categoryId = (int) $request_category;
                return $query->where('ticket_category_id', $categoryId);
            }
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
                case "project" :
                    // Tri par nom de projet via une sous-requête
                    $query->orderBy(
                        Project::select('name')
                            ->whereColumn('projects.id', 'bugs.project_id') // Liaison
                            ->limit(1),
                        $request->direction
                    );
                    return $query;
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

    protected function bugIdFormatted(): Attribute
    {


        return new Attribute(
            get: fn() => str_pad($this->id, 7, '0', STR_PAD_LEFT)
        );
    }
}
