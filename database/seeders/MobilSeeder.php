<?php

namespace Database\Seeders;

use App\Models\Mobil;
use App\Models\User;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user dengan ID 1
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@rentcar.com',
                'password' => bcrypt('password'),
            ]);
        }

        $mobils = [
            [
                'user_id' => $user->id,
                'namamobil' => 'Avanza Veloz',
                'merek' => 'Toyota',
                'tipe' => 'MPV',
                'tahun' => 2023,
                'platnomor' => 'B offici1234 ABC',
                'hargasewaperhari' => 300000,
                'status' => 'tersedia',
                'gambar' => null,
                'kapasitas' => 7, // Kapasitas untuk 7 penumpang
                'transmisi' => 'automatic', // Transmisi otomatis
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'Xenia R',
                'merek' => 'Daihatsu',
                'tipe' => 'MPV',
                'tahun' => 2022,
                'platnomor' => 'B 5678 DEF',
                'hargasewaperhari' => 275000,
                'status' => 'tersedia',
                'gambar' => null,
                'kapasitas' => 7, // Kapasitas untuk 7 penumpang
                'transmisi' => 'manual', // Transmisi manual
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'Fortuner VRZ',
                'merek' => 'Toyota',
                'tipe' => 'SUV',
                'tahun' => 2024,
                'platnomor' => 'B 9012 GHI',
                'hargasewaperhari' => 750000,
                'status' => 'tersedia',
                'gambar' => null,
                'kapasitas' => 7, // Kapasitas untuk 7 penumpang
                'transmisi' => 'automatic', // Transmisi otomatis
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'Camry Hybrid',
                'merek' => 'Toyota',
                'tipe' => 'Sedan',
                'tahun' => 2023,
                'platnomor' => 'B 3456 JKL',
                'hargasewaperhari' => 650000,
                'status' => 'disewa',
                'gambar' => null,
                'kapasitas' => 5, // Kapasitas untuk 5 penumpang
                'transmisi' => 'automatic', // Transmisi otomatis
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'Brio Satya',
                'merek' => 'Honda',
                'tipe' => 'Hatchback',
                'tahun' => 2021,
                'platnomor' => 'B 7890 MNO',
                'hargasewaperhari' => 225000,
                'status' => 'maintenance',
                'gambar' => null,
                'kapasitas' => 5, // Kapasitas untuk 5 penumpang
                'transmisi' => 'manual', // Transmisi manual
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'Innova Reborn',
                'merek' => 'Toyota',
                'tipe' => 'MPV',
                'tahun' => 2020,
                'platnomor' => 'B 2468 PQR',
                'hargasewaperhari' => 450000,
                'status' => 'tersedia',
                'gambar' => null,
                'kapasitas' => 8, // Kapasitas untuk 8 penumpang
                'transmisi' => 'automatic', // Transmisi otomatis
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'CR-V Turbo',
                'merek' => 'Honda',
                'tipe' => 'SUV',
                'tahun' => 2023,
                'platnomor' => 'B 1357 STU',
                'hargasewaperhari' => 600000,
                'status' => 'tersedia',
                'gambar' => null,
                'kapasitas' => 7, // Kapasitas untuk 7 penumpang
                'transmisi' => 'automatic', // Transmisi otomatis
            ],
            [
                'user_id' => $user->id,
                'namamobil' => 'Pajero Sport',
                'merek' => 'Mitsubishi',
                'tipe' => 'SUV',
                'tahun' => 2022,
                'platnomor' => 'B 9753 VWX',
                'hargasewaperhari' => 700000,
                'status' => 'tersedia',
                'gambar' => null,
                'kapasitas' => 7, // Kapasitas untuk 7 penumpang
                'transmisi' => 'automatic', // Transmisi otomatis
            ],
        ];

        foreach ($mobils as $mobil) {
            Mobil::create($mobil);
        }
    }
}
