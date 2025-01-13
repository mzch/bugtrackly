<?php

namespace App\Http\Controllers;

use App\Helpers\FileSizeHelper;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\BugCommentFile;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BugCommentFileController extends Controller
{
    public function download(Request $request, Project $project, Bug $bug, BugComment $bugComment, BugCommentFile $file){
        // Construire le chemin complet du fichier
        $filePath = $file->file_path;
        // Vérifier si le fichier existe
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }
        // Télécharger le fichier

        // Retourner le fichier
        return response()->file(Storage::disk('public')->path($filePath));

    }

    public function destroy(Request $request, Project $project, Bug $bug, BugComment $bugComment, BugCommentFile $file)
    {
        $file->delete();
        return response()->json(["success" => true]);

    }

    /**
     * Si des fichiers sont fournis, on les uploads
     * @param Request $request
     * @param BugComment $bugComment
     * @return void
     */
    public static function do_upload_files(Request $request, BugComment $bugComment){
        $files = [];
        if (!empty($request->validated("files"))) {
            foreach ($request->validated("files") as $file) {
                // Dossier basé sur l'ID du commentaire
                $directory = "bug_comments/{$bugComment->id}";

                // Générer un nom de fichier unique
                $uniqueFileName = BugCommentFile::getUniqueFileName($directory, $file->getClientOriginalName());

                // Stocker le fichier
                $path = $file->storeAs($directory, $uniqueFileName, 'public');

                // Créer une entrée pour le fichier
                $bugComment->files()->create([
                    'file_path' => $path,
                    'size' => $file->getSize(),
                    'size_human_readable' => FileSizeHelper::formatFileSize($file->getSize(), true),
                ]);
                $files[] = basename($path);
            }
        }
        return $files;
    }
}
