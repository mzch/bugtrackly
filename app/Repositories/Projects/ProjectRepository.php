<?php

namespace App\Repositories\Projects;

use App\Helpers\StringHelper;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{

    public function countAll(): int
    {
        return Project::count();
    }

    public function getAllPaginate(Request $request, int $nb_per_page = 10): LengthAwarePaginator
    {
        $query = Project::query()->with(['users']);
        $query = $this->sortQuery($query, $request);
        $query = $this->searchQuery($query, $request);
        return $query->paginate($nb_per_page)->withQueryString();
    }

    /**
     * Sort query
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    private function sortQuery(Builder $query, Request $request):Builder
    {
        if ($request->has(['field', 'direction'])) {
            switch ($request->field) {
                case "name" :
                    $sortField = 'name';
                    break;
                case "date" :
                    $sortField = 'updated_at';
                    break;
                default :
                    $sortField = $request->field;
            }
            if ($request->direction === 'asc') {
                $query->orderBy($sortField);
            } else {
                $query->orderByDesc($sortField);
            }
        }else{
            $query->orderBy('updated_at', 'desc');
        }
        return $query;
    }

    /**
     * Search query
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    private function searchQuery(Builder $query, Request $request):Builder{
        if ($request->has('search')) {
            $searchValue = strtolower(StringHelper::remove_accents($request->get('search')));
            $query->where('name', 'like', '%' . $searchValue . '%');
        }
        return $query;
    }
}
