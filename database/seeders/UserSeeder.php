<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            [
                'nip' => '198001012006041001',
                'name' => 'Admin BPS',
                'email' => 'admin@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198505152008041002',
                'name' => 'Dewi Kartika',
                'email' => 'dewi.kartika@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199001012015041001',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199505012018041001',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199808012020041001',
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199203152019041003',
                'name' => 'Rina Wulandari',
                'email' => 'rina.wulandari@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199607202021041004',
                'name' => 'Fajar Prasetyo',
                'email' => 'fajar.prasetyo@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198812102014041005',
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => false, // contoh user nonaktif
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
