<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_bmn' => 'required|string',
            'is_damaged' => 'nullable|boolean',
            'jenis_kerusakan' => 'required_if:is_damaged,true|in:ringan,berat',
            'deskripsi' => 'nullable|string|max:1000',
        ];
    }
}
