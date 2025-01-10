<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    protected static function boot()
    {
        parent::boot();
        // Suppression de tous les fichiers liés au bug
        static::deleting(function ($bugCommentFile) {
            $path = $bugCommentFile->file_path;
            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            // Vérifier si le répertoire est vide et le supprimer s'il l'est
            $directory = dirname($path); // Récupérer le chemin du répertoire
            if (Storage::disk('local')->exists($directory) && empty(Storage::disk('local')->files($directory))) {
                Storage::disk('local')->deleteDirectory($directory);
            }
        });
    }

    public static function getUniqueFileName($directory, $originalName)
    {
        $fileName = pathinfo($originalName, PATHINFO_FILENAME); // Nom du fichier sans extension
        $extension = pathinfo($originalName, PATHINFO_EXTENSION); // Extension du fichier
        $counter = 1;

        // Vérifier si le fichier existe déjà
        $newFileName = $fileName . '.' . $extension;
        while (Storage::exists("$directory/$newFileName")) {
            $newFileName = $fileName . '-' . $counter . '.' . $extension;
            $counter++;
        }

        return $newFileName;
    }


}
