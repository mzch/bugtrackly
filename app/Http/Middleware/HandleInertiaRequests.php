<?php

namespace App\Http\Middleware;

use App\Helpers\TranslationHelper;
use App\Models\User;
use App\Repositories\Locales\LocaleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{

    public function __construct(protected LocaleRepository $localeRepository){}
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
        $locale = app()->getLocale();
        if($current_user) {
            $current_user->load(['projects' => function ($query) {
                $query->orderBy('name', 'asc');
            }]);
        }
        return [
            ...parent::share($request),
            'flash'  => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'info'    => fn () => $request->session()->get('info'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            'auth' => [
                'user' => $current_user,
            ],
            'current_locale' => function () use ($locale) {
                return $locale;
            },
            'available_locales' => function ()  {
                return $this->localeRepository->getAllLocales();
            },
            'translations' => function () use ($locale) {
                return TranslationHelper::getTranslations($locale); // Charge les traductions
            },
            'app_url' => config('app.url'),
            'baseline' => config('bugtrackly.baseline'),
            'app_version' => config('app.version'),
            'admin_user_id' => function () use ($request) {
                if($request->session()->get('admin_user_id')){
                    return User::find($request->session()->get('admin_user_id'));
                }
                return null;
            }
        ];
    }
}
