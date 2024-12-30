<?php

namespace App\Repositories\BugStatus;

use App\Repositories\BugStatus\BugStatusRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BugStatusRepository implements BugStatusRepositoryInterface
{
    protected string $bugStatusFile = 'bugs/status.json';



    /**
     * Renvoie la liste de tous les status de bug disponibles avec leur(s) enfant(s)
     * @param bool $with_children
     * @return Collection
     */
    public function getAllBugStatus(bool $with_children = true): Collection
    {
        $jsonContent = Storage::get($this->bugStatusFile);
        $data = json_decode($jsonContent, true);
        $collection = collect($data['status'] ?? []);
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
}
