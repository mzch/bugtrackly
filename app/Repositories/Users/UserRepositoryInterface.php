<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function countAll(): int;
    public function getAll(): Collection;
    public function getAllPaginate(Request $request, int $nb_per_page = 10): LengthAwarePaginator;

    public function getUserById($user_id):User;

}
