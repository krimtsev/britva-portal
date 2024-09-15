<?php

namespace App\Http\Requests\UploadFile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UploadUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $unique = Rule::unique('upload')->ignore($this->upload->id, 'id');

        return [
            'title'              => ['required', $unique],
            'category'           => ['required'],
            'files.*'            => ['nullable', 'mimes:image,jpg,jpeg,png,tif,pdf,doc,docx,zip,xlsx'],
        ];
    }
}
