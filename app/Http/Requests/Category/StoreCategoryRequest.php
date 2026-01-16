<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name'
            ],
            'description' => [
                'nullable',
                'string',
                'max:500'
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:categories,slug',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'
            ],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique' => 'Une catégorie avec ce nom existe déjà.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé par une autre catégorie.',
            'slug.regex' => 'Le slug doit être au format kebab-case (ex: ma-categorie).',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom',
            'description' => 'description',
            'slug' => 'slug',
        ];
    }
}
