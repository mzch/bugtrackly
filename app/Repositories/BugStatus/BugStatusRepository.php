<?php

namespace App\Repositories\BugStatus;

use App\Repositories\BugStatus\BugStatusRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BugStatusRepository implements BugStatusRepositoryInterface
{
    protected string $bugStatusFile = 'bug-status/status.json';

    public function getAllBugStatus(): Collection
    {
        $jsonContent = Storage::get($this->bugStatusFile);
        $data = json_decode($jsonContent, true);
        return collect($data['status'] ?? []);
    }
}
