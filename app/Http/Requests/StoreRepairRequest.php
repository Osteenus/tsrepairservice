<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRepairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach (['name', 'phone', 'email', 'location', 'brand', 'model', 'description'] as $field) {
            if (is_string($this->input($field))) {
                $normalized[$field] = trim($this->input($field));
            }
        }
        $this->merge($normalized);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^[+0-9().\s\-xext#]+$/i', function ($attribute, $value, $fail) {
                if (is_string($value) && strlen(preg_replace('/\D/', '', $value)) < 7) {
                    $fail('Please enter a valid phone number.');
                }
            }],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'location' => ['required', 'string', 'max:120'],
            'serviceId' => ['required', 'integer', Rule::in(array_column(config('repair.services'), 'id'))],
            'brand' => ['nullable', 'string', 'max:120'],
            'model' => ['nullable', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:5000'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }

    public function attributes(): array
    {
        return ['serviceId' => 'appliance type', 'location' => 'city or ZIP code'];
    }

    public function messages(): array
    {
        return ['website.max' => 'Unable to send this request. Please call us for assistance.'];
    }
}
