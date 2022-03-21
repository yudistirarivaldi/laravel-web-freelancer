<?php

namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

use Auth;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            // Membuat email agar tidak bisa sama dengan yang lain dengan cara megambil id yang lagi login
            'email' => ['required', 'string', 'max:255', 'email', Rule::unique('users')->where('id', '<>',
                Auth::user()->id)],

        ];

    }
}
