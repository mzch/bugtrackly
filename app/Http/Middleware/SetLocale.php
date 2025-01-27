<?php

namespace App\Http\Middleware;

use App\Repositories\Locales\LocaleRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(protected LocaleRepository $localeRepository){}
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = $this->localeRepository->getAllLocales()->keys()->toArray();
        $locale = $request->getPreferredLanguage($locales) ?? 'en';
        if (session()->has('locale')) {
            $locale = session('locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
