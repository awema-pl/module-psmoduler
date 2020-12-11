<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreate extends FormRequest
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
            'name_module' => 'required|max:255|string|regex:/^[a-zA-Z]+$/u',
            'with_package' => 'required|boolean'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name_module' => _p('psmoduler::requests.creator.attributes.name_module', 'name module'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name_module.regex' => _p('psmoduler::requests.creator.messages.name_module_regex', 'Only letters allowed.'),
        ];
    }
}
