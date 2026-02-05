<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class BarangImportService
{
    /**
     * @return array{inserted:int,skipped:int}
     */
    public function importFromCsv(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        if ($path === false) {
            throw new RuntimeException('File tidak dapat dibaca.');
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new RuntimeException('File tidak dapat dibaca.');
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            throw new RuntimeException('File kosong atau format CSV tidak valid.');
        }

        $normalized = array_map(function ($value) {
            return strtolower(trim((string) $value));
        }, $header);

        $hasHeader = in_array('kode_barang', $normalized, true);
        if (!$hasHeader) {
            rewind($handle);
        }

        $inserted = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) {
                $skipped++;
                continue;
            }

            $data = [
                'kode_barang' => trim((string) ($row[0] ?? '')),
                'nup' => (int) trim((string) ($row[1] ?? '')),
                'brand' => trim((string) ($row[2] ?? '')),
                'tipe' => trim((string) ($row[3] ?? '')),
                'kondisi_terakhir' => $this->normalizeKondisi($row[4] ?? 'baik'),
                'ketersediaan' => $this->normalizeKetersediaan($row[5] ?? 'tersedia'),
            ];

            if ($data['kode_barang'] === '' || $data['nup'] <= 0 || $data['brand'] === '' || $data['tipe'] === '') {
                $skipped++;
                continue;
            }

            $exists = Barang::where('kode_barang', $data['kode_barang'])
                ->where('nup', $data['nup'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Barang::create([
                'kode_barang' => $data['kode_barang'],
                'nup' => $data['nup'],
                'brand' => $data['brand'],
                'tipe' => $data['tipe'],
                'kondisi_terakhir' => $data['kondisi_terakhir'],
                'ketersediaan' => $data['ketersediaan'],
            ]);

            $inserted++;
        }

        fclose($handle);

        return [
            'inserted' => $inserted,
            'skipped' => $skipped,
        ];
    }

    private function normalizeKondisi($value): string
    {
        $raw = strtolower(trim((string) $value));
        $raw = str_replace(' ', '_', $raw);

        return match ($raw) {
            'baik' => 'baik',
            'rusak_ringan' => 'rusak_ringan',
            'rusak_berat' => 'rusak_berat',
            default => 'baik',
        };
    }

    private function normalizeKetersediaan($value): string
    {
        $raw = strtolower(trim((string) $value));
        $allowed = ['tersedia', 'dipinjam', 'hilang', 'reparasi'];

        return in_array($raw, $allowed, true) ? $raw : 'tersedia';
    }
}
