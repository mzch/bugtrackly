<?php

namespace App\Http\Controllers;

use App\Http\Requests\BugComment\StoreBugCommentRequest;
use App\Http\Requests\BugComment\UpdateBugCommentRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use App\Notifications\Bug\BugCommentCreatedNotification;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use App\Trait\BugLog\HasBugLogMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $dataMail = [
            'note_author' => auth()->user(),
            'new_comment' => false,
            'priority' => false,
            'status' => false,
            'assigned_user' => false,
            'files' => [],
        ];
        if(!blank($request->input("content")) ){
            $bugComment = new BugComment($request->validated());
            $bug->bug_comments()->save($bugComment);
            $dataMail['new_comment'] = $bugComment;
            self::logAction(
                $bug->id,
                auth()->id(),
                "Nouvelle note"
            );
            $dataMail['files'] = BugCommentFileController::do_upload_files($request, $bugComment);
        }

        $change = $bug->getChanges();
        if(array_key_exists("priority", $change)){
            $old_priority = $this->bug_infos_repository->getBugPriorityById($original["priority"]);
            $new_priority = $this->bug_infos_repository->getBugPriorityById($change["priority"]);
            $dataMail['priority'] = ['old' => $old_priority, 'new' => $new_priority];;
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
            $dataMail['status'] = ['old' => $old_status, 'new' => $new_status];
            self::logAction(
                $bug->id,
                auth()->id(),
                "Statut",
                $old_status['label'] . " => " . $new_status['label']
            );
        }
        if(array_key_exists("assigned_user_id", $change)){
            $old_user = null;
            if($original["assigned_user_id"]){
                $old_user = $this->user_repository->getUserById($original["assigned_user_id"]);
            }
            $new_user = null;
            if($change["assigned_user_id"]){
                $new_user = $this->user_repository->getUserById($change["assigned_user_id"]);
            }
            $dataMail['assigned_user'] = ['old' => $old_user, 'new' => $new_user];
            if($old_user && $new_user){
                $str = $old_user->full_name . " => " . $new_user->full_name;
            }elseif ($old_user === null && $new_user){
                $str = " => " . $new_user->full_name;
            }else{
                $str = $old_user->full_name . " => (aucun)";
            }
            self::logAction(
                $bug->id,
                auth()->id(),
                "Assigné à",
                $str,
            );
        }

        // email notif
        $usersToNotify = $project
            ->users->where('role_id', 1)
            ->where('id', '!=', auth()->id()); // les admins sur le projets autre que le user connecté
        if($dataMail['assigned_user'] && $dataMail['assigned_user']['new']){
            $usersToNotify->push($dataMail['assigned_user']['new']);
        }
        $usersToNotify = $usersToNotify->concat($bug->user_followers);
        $usersToNotify = $usersToNotify->unique('id');


        foreach ($usersToNotify as $user) {
            $user->notify(new BugCommentCreatedNotification($project, $bug, $dataMail));
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
        BugCommentFileController::do_upload_files($request, $bugComment);

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
