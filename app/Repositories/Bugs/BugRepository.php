<?php

namespace App\Repositories\Bugs;

use App\Models\Bug;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BugRepository implements BugRepositoryInterface
{

    /**
     * Renvoie la liste paginée et filtrée des bugs d'un projet
     * @param Project $project
     * @param Request $request
     * @param int $nb_per_page
     * @return LengthAwarePaginator
     */
    public function getAllBugsPaginatedForProject(Project $project, Request $request, int $nb_per_page = 10): LengthAwarePaginator
    {
        $query = Bug::whereBelongsTo($project)
            ->withCount('bug_comments')
            ->with(['user_followers' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->bugSearch($request)
            ->filterByStatus($request)
            ->filterByPriority($request)
            ->bugOrderBy($request);

        // Récupère la liste paginée des bugs
        $bugs = $query->paginate($nb_per_page)->withQueryString();

        // Ajoute une propriété indiquant si l'utilisateur suit chaque bug
        $bugs->getCollection()->transform(function ($bug) {
            $bug->is_followed_by_me = $bug->user_followers->isNotEmpty();
            unset($bug->user_followers); // Facultatif, pour nettoyer la réponse
            return $bug;
        });

        return $bugs;
    }


    public function getAllFollowedBugsPaginated(Request $request, int $nb_per_page = 10): LengthAwarePaginator
    {
        $query = Bug::with(['project'])
            ->withCount('bug_comments')
            ->whereHas('user_followers', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->bugSearch($request)
            ->filterByStatus($request)
            ->filterByPriority($request)
            ->filterByProjectSlug($request)
            ->bugOrderBy($request);

        // Récupère la liste paginée des bugs
        $bugs = $query->paginate($nb_per_page)->withQueryString();


        return $bugs;
    }
}
