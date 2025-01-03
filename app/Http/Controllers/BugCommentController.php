<?php

namespace App\Http\Controllers;

use App\Http\Requests\BugComment\UpdateBugCommentRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BugCommentController extends Controller
{
    public function update(UpdateBugCommentRequest $request,  Project $project, Bug $bug, BugComment $bugComment): JsonResponse
    {
        $bugComment->update($request->validated());
        return response()->json(["success" => true]);
    }
}
