<?php

namespace App\Trait\BugLog;

use App\Models\BugLog;

trait HasBugLogMethods
{
    public static function logAction(int $bugId, int $userId, string $action, string $details = null): void{
        BugLog::create([
            'bug_id' => $bugId,
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
        ]);
    }
}
