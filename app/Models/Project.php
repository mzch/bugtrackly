<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    /**
     * Indique que la clé primaire n'est pas auto-incrémentée.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indique que la clé primaire est de type string.
     *
     * @var string
     */
    protected $keyType = 'string';


    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_desc',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->id)) {
                $originalSlug = \Str::slug($project->name);
                $slug = $originalSlug;
                $counter = 2;

                // Vérifie si le slug existe déjà
                while (self::where('id', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $project->id = $slug;
            }
        });
    }

}
