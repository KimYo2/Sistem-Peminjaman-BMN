<?php

namespace App\Services;

use InvalidArgumentException;

class BmnParser
{
    /**
     * Parse nomor BMN.
     *
     * @return array{kode_barang: string, nup: int|null}
     */
    public static function parse(string $raw, bool $requireNup = true): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            throw new InvalidArgumentException('Nomor BMN kosong.');
        }

        // Handle BPS QR format: INV-...*...*KODE*NUP
        if (str_contains($raw, '*')) {
            $parts = explode('*', $raw);
            if (count($parts) >= 4) {
                $raw = trim($parts[2]) . '-' . trim($parts[3]);
            }
        }

        if (str_contains($raw, '-')) {
            [$kode, $nupRaw] = array_pad(explode('-', $raw, 2), 2, '');
            $kode = trim($kode);
            $nupRaw = trim($nupRaw);

            if ($kode === '') {
                throw new InvalidArgumentException('Kode barang tidak valid.');
            }

            if ($nupRaw === '' || !ctype_digit($nupRaw)) {
                throw new InvalidArgumentException('Format NUP tidak valid.');
            }

            return [
                'kode_barang' => $kode,
                'nup' => (int) $nupRaw,
            ];
        }

        if ($requireNup) {
            throw new InvalidArgumentException('Format Nomor BMN tidak valid.');
        }

        return [
            'kode_barang' => $raw,
            'nup' => null,
        ];
    }
}
