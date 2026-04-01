<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'keterangan' => 'nullable|string',
            'durasi_pinjam_default' => 'required|integer|min:1|max:365',
        ];
    }
}
