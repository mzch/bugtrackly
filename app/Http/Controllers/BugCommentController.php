<?php

namespace App\Http\Controllers;

use App\Events\BugCommentCreated;
use App\Http\Requests\BugComment\StoreBugCommentRequest;
use App\Http\Requests\BugComment\UpdateBugCommentRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BugCommentController extends Controller
{

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

        $bugComment = null;
        $files = [];
        if(!blank($request->input("content")) ){
            $bugComment = new BugComment($request->validated());
            $bug->bug_comments()->save($bugComment);
            $files = BugCommentFileController::do_upload_files($request, $bugComment);
        }
        // Trigger the BugCommentCreated event
        BugCommentCreated::dispatch($project, $bugComment, $bug, $original,$files);

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
        $bugComment->delete();
        return response()->json(["success" => true]);
    }
}
