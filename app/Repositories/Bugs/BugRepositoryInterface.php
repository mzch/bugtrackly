<?php

namespace App\Repositories\Bugs;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface BugRepositoryInterface
{
    public function getAllBugsPaginatedForProject(Project $project, Request $request, int $nb_per_page = 10): LengthAwarePaginator;
    public function getAllFollowedBugsPaginated(Request $request, int $nb_per_page = 10): LengthAwarePaginator;
}
