<?php

namespace App\Repositories\BugInfos;

use Illuminate\Support\Collection;

interface BugInfosRepositoryInterface
{
    public function getAllBugStatus(bool $with_children = true) :Collection;
    public function getBugStatusById($id): array;
    public function getAllBugPriorities() :Collection;
    public function getBugPriorityById($id): array;
}
