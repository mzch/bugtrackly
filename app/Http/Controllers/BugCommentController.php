<?php

namespace App\Http\Controllers;

use App\Http\Requests\BugComment\StoreBugCommentRequest;
use App\Http\Requests\BugComment\UpdateBugCommentRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use App\Trait\BugLog\HasBugLogMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BugCommentController extends Controller
{
    use HasBugLogMethods;

    public function __construct(
        protected BugInfosRepositoryInterface $bug_infos_repository,
        protected UserRepositoryInterface $user_repository,
    )
    {
    }
    /**
     * Ajoute une nouvelle note
     * @param StoreBugCommentRequest $request
     * @param Project $project
     * @param Bug $bug
     * @return JsonResponse
     */
    public function store(StoreBugCommentRequest $request, Project $project, Bug $bug): JsonResponse{
        $original = $bug->getOriginal();
        $bug->update($request->validated());

        if(!blank($request->input("content")) ){
            $bugComment = new BugComment($request->validated());
            $bug->bug_comments()->save($bugComment);

            self::logAction(
                $bug->id,
                auth()->id(),
                "Nouvelle note"
            );
        }

        $change = $bug->getChanges();
        if(array_key_exists("priority", $change)){
            $old_priority = $this->bug_infos_repository->getBugPriorityById($original["priority"]);
            $new_priority = $this->bug_infos_repository->getBugPriorityById($change["priority"]);
            self::logAction(
                $bug->id,
                auth()->id(),
                "Priorité",
                $old_priority['label'] . " => " . $new_priority['label']
            );
        }
        if(array_key_exists("status", $change)){
            $old_status = $this->bug_infos_repository->getBugStatusById($original["status"]);
            $new_status = $this->bug_infos_repository->getBugStatusById($change["status"]);
            self::logAction(
                $bug->id,
                auth()->id(),
                "Statut",
                $old_status['label'] . " => " . $new_status['label']
            );
        }
        if(array_key_exists("assigned_user_id", $change)){
            $old_user = $this->user_repository->getUserById($original["assigned_user_id"]);
            $new_user = $this->user_repository->getUserById($change["assigned_user_id"]);
            self::logAction(
                $bug->id,
                auth()->id(),
                "Assigné à",
                $old_user->full_name . " => " . $new_user->full_name
            );
        }


        return response()->json(["success" => true]);
    }

    /**
     * Mise à jour d'une nouvelle note
     * @param UpdateBugCommentRequest $request
     * @param Project $project
     * @param Bug $bug
     * @param BugComment $bugComment
     * @return JsonResponse
     */
    public function update(UpdateBugCommentRequest $request,  Project $project, Bug $bug, BugComment $bugComment): JsonResponse
    {
        $bugComment->update($request->validated());
        return response()->json(["success" => true]);
    }


    /**
     * Suppression d'une note
     * @param Request $request
     * @param Project $project
     * @param Bug $bug
     * @param BugComment $bugComment
     * @return JsonResponse
     */
    public function destroy(Request$request , Project $project, Bug $bug, BugComment $bugComment):JsonResponse
    {
//        dd($request->all());
        $bugComment->delete();
        return response()->json(["success" => true]);
    }
}
