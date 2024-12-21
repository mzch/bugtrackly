<?php

namespace App\Models;

use App\Trait\Project\HasProjectPhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, HasProjectPhoto;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'short_desc',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'project_photo_path',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'project_photo_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $originalSlug = \Str::slug($project->name);
                $slug = $originalSlug;
                $counter = 2;

                // Vérifie si le slug existe déjà
                while (self::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $project->slug = $slug;
            }
        });
        static::updating(function ($project) {
            $originalSlug = $project->slug;
            $slug = $originalSlug;
            $counter = 2;
            while (self::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $project->slug = $slug;
        });
        static::deleting(function (Project $project) {
            $project->deleteProjectPhoto();
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
