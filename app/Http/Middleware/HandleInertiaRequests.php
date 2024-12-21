<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $current_user = $request->user();
        if($current_user) {
            $current_user->load('projects');
        }
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $current_user,
            ],
            'app_url' => config('app.url'),
            'baseline' => config('bugtrackly.baseline'),
            'admin_user_id' => function () use ($request) {
                if($request->session()->get('admin_user_id')){
                    return User::find($request->session()->get('admin_user_id'));
                }
                return null;
            }
        ];
    }
}
