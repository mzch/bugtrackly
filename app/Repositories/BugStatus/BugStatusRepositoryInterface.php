<?php

namespace App\Repositories\BugStatus;

use Illuminate\Support\Collection;

interface BugStatusRepositoryInterface
{
    public function getAllBugStatus() :Collection;
}
