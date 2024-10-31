<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'firstname' => [
                'required', 
                'string', 
                'max:30', 
                'alpha', 
            ],
            'lastname' => [
                'required', 
                'string', 
                'max:30', 
                'alpha', 
            ],
            'username' => [
                'required', 
                'string', 
                'max:255', 
                'alpha_num', 
                'unique:users,username' 
            ],
            'sponsor' => [
                'required', 
                'string', 
                'max:255', 
                'alpha_num', 
                'exists:users,username' 
            ],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                //'unique:users,email' 
            ],
            'password' => [
                'required', 
                'string', 
                'min:8', // Tamanho mínimo de 8 caracteres
                //'regex:/[a-z]/', // Pelo menos uma letra minúscula
                //'regex:/[A-Z]/', // Pelo menos uma letra maiúscula
                //'regex:/[0-9]/', // Pelo menos um número
                //'regex:/[@$!%*?&]/', // Pelo menos um caractere especial
            ]
        ];
    }
    public function messages()
    {
        return [
            // First Name
            'firstname.required' => 'First name is required.',
            'firstname.alpha' => 'First name must contain only letters.',
            'firstname.max' => 'First name must not exceed 30 characters.',
            
            // Last Name
            'lastname.required' => 'Last name is required.',
            'lastname.alpha' => 'Last name must contain only letters.',
            'lastname.max' => 'Last name must not exceed 30 characters.',
    
            // Username
            'username.required' => 'Username is required.',
            'username.alpha_num' => 'Username must contain only letters and numbers.',
            'username.unique' => 'This username is already in use.',
            'username.max' => 'Username must not exceed 255 characters.',
        
            // Sponsor
            'sponsor.required' => 'Sponsor is required.',
            'sponsor.alpha_num' => 'Sponsor must contain only letters and numbers.',
            'sponsor.exists' => 'The sponsor provided was not found.',
        
            // Email
            'email.required' => 'Email address is required.',
            'email.email' => 'Email address must be valid.',
            'email.max' => 'Email address must not exceed 255 characters.',
            //'email.unique' => 'This email address is already in use.',
        
            // Password
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            //'password.regex' => 'Password must contain uppercase letters, lowercase letters, numbers, and special characters.',

        ];
    }
    
    

    public function failedValidation(Validator $validator)
    {

        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'erro' => $errors[0]
            ], 200)
        );
    }
}
