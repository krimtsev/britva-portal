<?php

namespace App\Http\Requests\UploadFile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UploadStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $unique = Rule::unique('upload');

        return [
            'name'         => ['required', 'min:3', $unique],
            'category_id'  => ['nullable'],
            'slug'         => ['required', 'alpha_dash', 'max:255', $unique],
            'files.*'      => ['nullable', 'mimes:image,jpg,jpeg,png,tif,pdf,doc,docx,zip,xlsx,xls,txt'],
        ];
    }
}
