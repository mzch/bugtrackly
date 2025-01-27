<?php

namespace App\Repositories\BugInfos;

use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BugInfosRepository implements BugInfosRepositoryInterface
{
    protected string $bugStatusFile = 'bugs/status.json';
    protected string $bugPrioritiesFile = 'bugs/priorities.json';



    /**
     * Renvoie la liste de tous les status de bug disponibles avec leur(s) enfant(s)
     * @param bool $with_children
     * @return Collection
     */
    public function getAllBugStatus(bool $with_children = true): Collection
    {
        $jsonContent = Storage::get($this->bugStatusFile);
        $data = json_decode($jsonContent, true);
        $status = $data['status'];
        foreach ($status as &$s) {
            $s['label'] = __('bugtrackly.bug_status_' . $s['slug']);
        }
        $collection = collect($status ?? []);
        if(!$with_children){
            return $collection;
        }
        return $collection->map(function ($item) use ($collection) {
            $childrenIds = collect($item['children']);
            $children  = $childrenIds->map(function ($child_id) use ($collection) {
               return $collection->where('id', $child_id)->first();
            });
            $item["children"] = $children;
            return $item;
        });
    }

    public function getBugStatusById($id):array
    {
        $status = $this->getAllBugStatus();
        return $status->where('id', $id)->first();
    }


    /**
     * Renvoie la liste des priorités disponibles pour les bugs
     * @return Collection
     */
    public function getAllBugPriorities(): Collection
    {
        $jsonContent = Storage::get($this->bugPrioritiesFile);
        $data = json_decode($jsonContent, true);
        return collect($data['priorities'] ?? []);
    }

    public function getBugPriorityById($id):array
    {
        $priorities = $this->getAllBugPriorities();
        return $priorities->where('id', $id)->first();
    }
}
