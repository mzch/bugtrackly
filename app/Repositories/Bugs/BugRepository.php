<?php

namespace App\Repositories\Bugs;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BugRepository implements BugRepositoryInterface
{

    public function getAllBugsPaginatedForProject(Project $project, Request $request, int $nb_per_page = 10): LengthAwarePaginator
    {
        $query = Bug::whereBelongsTo($project);

        return $query->paginate($nb_per_page)->withQueryString();
    }
}
