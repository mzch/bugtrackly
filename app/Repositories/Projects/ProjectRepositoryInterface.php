<?php

namespace App\Repositories\Projects;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function countAll(): int;

    public function getAllPaginate(Request $request, int $nb_per_page = 10): LengthAwarePaginator;

    public function getProjectsForCurrentUser(Request $request):Collection;
}
