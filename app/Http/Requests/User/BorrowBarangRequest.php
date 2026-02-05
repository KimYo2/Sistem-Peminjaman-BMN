<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class BorrowBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_bmn' => 'required|string',
        ];
    }
}
