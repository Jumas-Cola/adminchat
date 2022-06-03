<?php

namespace JumasCola\AdminChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminChatMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'max:65000',
            "files.*" =>
                "file|mimes:jpg,bmp,png,pdf,jpeg,webp,txt,doc,docx,xls,xls|max:40240",
        ];
    }
}
