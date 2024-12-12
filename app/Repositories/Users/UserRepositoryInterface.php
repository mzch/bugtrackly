<?php

namespace App\Repositories\Users;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function countAll(): int;
    public function getAll(Request $request, int $nb_per_page = 10): LengthAwarePaginator;

}
