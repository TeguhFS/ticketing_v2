<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'phone'          => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string', 'max:500'],
            'gender'         => ['nullable', 'in:male,female'],
            'birth_date'     => ['nullable', 'date'],
            'id_card_number' => ['nullable', 'string', 'size:16', Rule::unique(User::class)->ignore($this->user()->id)],
            'id_card_image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ];
    }

    public function messages(): array
    {
        return [
            'id_card_number.size'   => 'NIK KTP harus beralur 16 digit.',
            'id_card_number.unique' => 'NIK KTP ini sudah terdaftar pada akun lain.',
        ];
    }
}
