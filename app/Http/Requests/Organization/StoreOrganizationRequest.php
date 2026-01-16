<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
                'unique:organizations,name'
            ],
            'type' => [
                'required',
                'in:individual,company,franchise,cooperative,group'
            ],
            'legal_name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'legal_form' => [
                'nullable',
                'string',
                'max:100'
            ],
            'tax_id' => [
                'nullable',
                'string',
                'max:100'
            ],
            'registration_number' => [
                'nullable',
                'string',
                'max:100'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],
            'address' => [
                'nullable',
                'string',
                'max:500'
            ],
            'city' => [
                'nullable',
                'string',
                'max:100'
            ],
            'country' => [
                'required',
                'string',
                'size:2'
            ],
            'logo' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,jpg,png,gif,webp'
            ],
            'website' => [
                'nullable',
                'url',
                'max:255'
            ],
            'currency' => [
                'required',
                'string',
                'size:3'
            ],
            'timezone' => [
                'required',
                'string',
                'max:50'
            ],
            'subscription_plan' => [
                'required',
                'in:free,starter,professional,enterprise'
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
            'name.required' => 'Le nom de l\'organisation est obligatoire.',
            'name.unique' => 'Ce nom d\'organisation existe déjà.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'type.required' => 'Le type d\'organisation est obligatoire.',
            'type.in' => 'Le type sélectionné n\'est pas valide.',

            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',

            'phone.max' => 'Le téléphone ne peut pas dépasser 50 caractères.',

            'address.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',

            'city.max' => 'La ville ne peut pas dépasser 100 caractères.',

            'country.required' => 'Le pays est obligatoire.',
            'country.size' => 'Le code pays doit contenir exactement 2 caractères.',

            'logo.image' => 'Le fichier doit être une image.',
            'logo.max' => 'L\'image ne peut pas dépasser 2 Mo.',
            'logo.mimes' => 'L\'image doit être au format JPEG, JPG, PNG, GIF ou WEBP.',

            'website.url' => 'Le site web doit être une URL valide.',
            'website.max' => 'Le site web ne peut pas dépasser 255 caractères.',

            'currency.required' => 'La devise est obligatoire.',
            'currency.size' => 'Le code devise doit contenir exactement 3 caractères.',

            'timezone.required' => 'Le fuseau horaire est obligatoire.',
            'timezone.max' => 'Le fuseau horaire ne peut pas dépasser 50 caractères.',

            'subscription_plan.required' => 'Le plan d\'abonnement est obligatoire.',
            'subscription_plan.in' => 'Le plan d\'abonnement sélectionné n\'est pas valide.',
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
            'type' => 'type',
            'legal_name' => 'raison sociale',
            'legal_form' => 'forme juridique',
            'tax_id' => 'NIF/RCCM',
            'registration_number' => 'numéro d\'immatriculation',
            'email' => 'email',
            'phone' => 'téléphone',
            'address' => 'adresse',
            'city' => 'ville',
            'country' => 'pays',
            'logo' => 'logo',
            'website' => 'site web',
            'currency' => 'devise',
            'timezone' => 'fuseau horaire',
            'subscription_plan' => 'plan d\'abonnement',
        ];
    }
}
