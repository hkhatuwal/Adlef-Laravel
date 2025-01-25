<?php

namespace App\Http\Requests;

use Flasher\Prime\Notification\Type;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class AccountOpeningRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to false if you need authorization logic.
    }

    protected function failedValidation(Validator $validator)
    {
        toastr("Some fields are empty or invalid", Type::ERROR);
        throw new HttpResponseException(
            back()
                ->withErrors($validator)
                ->withInput($this->except('password'))
        );
    }


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'alias' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'marital_status' => ['required', 'string', 'in:Single,Married,Divorced,Widowed'],

            // Account Purpose and Economic Profile
            'purpose' => ['required', 'array', 'min:1'],
            'purpose.*' => ['string', 'in:Custody,Asset Servicing,Escrow,Investments,Treasury Services,Other'],
            'source_funds' => ['required', 'array', 'min:1'],
            'source_funds.*' => ['string'],
            'wealth_source' => ['required', 'array', 'min:1'],
            'wealth_source.*' => ['string'],
            'annual_income' => ['required', 'string'],

            // Contact Information
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_country' => ['required', 'string'],
            'phone_number' => ['required', 'string'],

            // Address
            'street_address' => ['required', 'string', 'max:255'],
            'apartment' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],

            // Tax Information
            'hk_tax_resident' => ['nullable', 'string'],
            'tin_status' => ['required'],
            'tin_number' => ['required_if:tin_status,provided'],
            'tin_reason' => ['required_if:tin_status,not_provided', 'in:reason_not_required,reason_unable,reason_no_issue'],

            // Password and Terms
            'password' => ['required', 'string', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'terms' => ['required', 'accepted'],
        ];
    }

    /**
     * Get the custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'purpose.required' => 'Please select at least one purpose for opening the account.',
            'source_funds.required' => 'Please select at least one source of funds.',
            'wealth_source.required' => 'Please select at least one source of wealth.',
            'tin_status.required_if' => 'Please specify if you have a TIN number.',
            'tin_number.required_if' => 'Please provide your TIN number.',
            'tin_reason.required_if' => 'Please specify why you cannot provide a TIN number.',
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.',
            'password.min' => 'Password must be at least 8 characters.',
            'date_of_birth.before' => 'Date of birth must be a date before today.',
        ];
    }
}
