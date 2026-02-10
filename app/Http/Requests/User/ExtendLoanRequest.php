<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ExtendLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hari' => 'nullable|integer|min:1|max:14',
            'alasan' => 'nullable|string|max:255',
        ];
    }
}
