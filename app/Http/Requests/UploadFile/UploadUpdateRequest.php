<?php

namespace App\Http\Requests\UploadFile;

use App\Http\Controllers\UploadFiles\UploadFilesController;
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
            'name'         => ['required', 'min:3', $unique],
            'category_id'  => ['nullable'],
            'slug'         => ['required', 'alpha_dash', 'max:255', $unique],
            'files.*'      => ['nullable', UploadFilesController::RULES_ALLOW_TYPES],
        ];
    }
}
