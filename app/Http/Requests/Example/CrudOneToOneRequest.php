<?php

namespace App\Http\Requests\Example;

use Illuminate\Foundation\Http\FormRequest;

class CrudOneToOneRequest extends FormRequest
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
            'crud_one_to_one_pk_nama' => ['required', 'string'],
            'crud_one_to_one_fk_telp' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'crud_one_to_one_pk_nama.required' => 'Nama tidak boleh kosong',
            'crud_one_to_one_fk_telp.required' => 'Telp tidak boleh kosong',
        ];
    }
}
