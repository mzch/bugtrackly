<?php

namespace App\Http\Controllers;

use App\Http\Requests\BugComment\StoreBugCommentRequest;
use App\Http\Requests\BugComment\UpdateBugCommentRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BugCommentController extends Controller
{
    /**
     * Ajoute une nouvelle note
     * @param StoreBugCommentRequest $request
     * @param Project $project
     * @param Bug $bug
     * @return JsonResponse
     */
    public function store(StoreBugCommentRequest $request, Project $project, Bug $bug): JsonResponse{
        $bugComment = new BugComment($request->validated());
        $bug->bug_comments()->save($bugComment);
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
