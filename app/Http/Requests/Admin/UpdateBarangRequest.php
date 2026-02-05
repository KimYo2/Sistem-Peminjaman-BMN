<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => 'required|string|max:100',
            'tipe' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'ketersediaan' => 'required|in:tersedia,dipinjam,hilang,reparasi',
            'keterangan' => 'nullable|string',
            'pic_user_id' => 'nullable|exists:users,id',
        ];
    }
}
