<?php

namespace App\Repositories\Users;

use App\Helpers\StringHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Get all user
     * @param Request $request
     * @param int $nb_per_page
     * @return LengthAwarePaginator
     */
    public function getAll(Request $request, int $nb_per_page = 10): LengthAwarePaginator
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
                case "lastname" :
                    $sortField = 'lastname';
                    break;
                case "firstname" :
                    $sortField = 'firstname';
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
