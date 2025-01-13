<?php

namespace App\Rules;

use App\Models\Bug;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class ValidBugStatus implements ValidationRule
{

    protected BugInfosRepositoryInterface $bugInfosRepository;
    public function __construct(protected Bug $bug ){
        $this->bugInfosRepository = app(BugInfosRepositoryInterface::class);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(auth()->user()->role_id!= 1){
            $current_bug_status = $this->bug->status;

            $current_bug_status_object = $this->bugInfosRepository
                ->getAllBugStatus(false)
                ->where('id', $current_bug_status)
                ->first();

            if(intval($value)!==$current_bug_status && !in_array($value, $current_bug_status_object["children"] )) {
                $fail('Le nouveau statut de bug n\'est pas valide');
            }
        }

    }
}
