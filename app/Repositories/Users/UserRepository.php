<?php

namespace App\Repositories\Users;

use App\Helpers\StringHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{

    public function getAll() :Collection
    {
        return User::all();
    }

    public function getUserById($user_id): User
    {
        return User::where('id', $user_id)->first();
    }
    /**
     * Get all user with pagination
     * @param Request $request
     * @param int $nb_per_page
     * @return LengthAwarePaginator
     */
    public function getAllPaginate(Request $request, int $nb_per_page = 10): LengthAwarePaginator
    {
        $queryUsers = User::query();
        $queryUsers = $this->sortQuery($queryUsers, $request);
        $queryUsers = $this->searchQuery($queryUsers, $request);
        return $queryUsers->paginate($nb_per_page)->withQueryString();
    }

    public function countAll(): int
    {
        return User::count();
    }



    /**
     * Sort query
     * @param Builder $queryUsers
     * @param Request $request
     * @return Builder
     */
    private function sortQuery(Builder $queryUsers, Request $request):Builder
    {
        if ($request->has(['field', 'direction'])) {
            switch ($request->field) {
                case "name" :
                    $sortField = 'first_name';
                    break;
                case "role" :
                    $sortField = 'role_id';
                    break;
                default :
                    $sortField = $request->field;
            }
            if ($request->direction === 'asc') {
                $queryUsers->orderBy($sortField);
            } else {
                $queryUsers->orderByDesc($sortField);
            }
        }
        return $queryUsers;
    }

    /**
     * Search query
     * @param Builder $queryUsers
     * @param Request $request
     * @return Builder
     */
    private function searchQuery(Builder $queryUsers, Request $request):Builder{
        if ($request->search) {
            $searchValue = strtolower(StringHelper::remove_accents($request->search));
            $queryUsers->where('first_name', 'like', '%' . $searchValue . '%');
            $queryUsers->orWhere('last_name', 'like', '%' . $searchValue . '%');
            $queryUsers->orWhere('email', 'like', '%' . $searchValue . '%');
        }
        return $queryUsers;
    }



}
