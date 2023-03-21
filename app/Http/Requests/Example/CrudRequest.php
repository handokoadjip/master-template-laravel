<?php

namespace App\Http\Requests\Example;

use Illuminate\Foundation\Http\FormRequest;

class CrudRequest extends FormRequest
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
            'crud_nama' => ['required', 'string'],
            'crud_foto' => ['image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'crud_nama.required' => 'Nama tidak boleh kosong',
            'crud_foto.image' => 'Foto harus berupa file gambar',
            'crud_foto.mimes' => 'Foto harus ber extension jpg,png,jpeg,gif,svg',
            'crud_foto.max' => 'Foto maksimal ukuran 2mb',
        ];
    }
}
