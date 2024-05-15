<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
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
            'code'               => ['nullable', 'string', 'max:255', 'unique:promo_codes'],
            'expiry_date'        => ['nullable', 'date', 'after_or_equal:today'],
            'max_usage_count'    => ['nullable', 'integer'],
            'max_usage_per_user' => ['nullable', 'integer', 'lte:max_usage_count'],
            'user_ids'           => ['nullable', 'array',],
            'user_ids.*'         => ['required_with:user_ids', 'integer', 'distinct', 'exists:users,id'],
            'type'               => ['required', 'in:percentage,value'],
            'value'              => $this->input('type') === 'percentage' ? 'required|numeric|max:100' : 'required|numeric|min:0',
        ];
    }
}
