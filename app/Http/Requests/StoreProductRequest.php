<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:product,service',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
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
            'name.required' => 'Product name is required.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'sku.unique' => 'This SKU is already in use.',
            'type.required' => 'Product type is required.',
            'type.in' => 'Product type must be either product or service.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
        ];
    }
}