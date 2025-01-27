<?php

namespace App\Http\Requests\Locale;

use App\Repositories\Locales\LocaleRepository;
use Illuminate\Foundation\Http\FormRequest;

class ChangeLocaleRequest extends FormRequest
{
    public function __construct(protected LocaleRepository $localeRepository){
        parent::__construct();
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $locales = $this->localeRepository->getAllLocales()->keys()->toArray();
        return [
            'locale' => 'required|string|in:' . implode(',', $locales),
        ];
    }
}
