<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_barang' => 'required|string|max:50',
            'nup' => 'required|integer|min:1',
            'brand' => 'required|string|max:100',
            'tipe' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'pic_user_id' => 'nullable|exists:users,id',
        ];
    }
}
