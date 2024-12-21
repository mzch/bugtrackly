<?php

namespace App\Repositories\Projects;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function countAll(): int;

    public function getAllPaginate(Request $request, int $nb_per_page = 10): LengthAwarePaginator;
}
