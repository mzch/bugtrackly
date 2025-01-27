<?php

namespace App\Http\Controllers;

use App\Http\Requests\Locale\ChangeLocaleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    /**
     * Changing the language of the application
     * @param ChangeLocaleRequest $request
     * @return JsonResponse
     */
    public function setLocale(ChangeLocaleRequest $request):JsonResponse
    {
        $locale = $request->validated('locale');
        session(['locale' => $locale]);
        App::setLocale($locale);
        return response()->json(['success' => true,]);

    }
}
