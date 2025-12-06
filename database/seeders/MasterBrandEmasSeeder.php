<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterBrandEmas;

class MasterBrandEmasSeeder extends Seeder
{
    public function run(): void
    {
        MasterBrandEmas::create([
            'kode_brand' => 'ANTAM',
            'nama_brand' => 'ANTAM',
            'image_url' => '',
            'deskripsi' => '',
            'is_active' => true,
        ]);

        MasterBrandEmas::create([
            'kode_brand' => 'UBS',
            'nama_brand' => 'UBS',
            'image_url' => '',
            'deskripsi' => '',
            'is_active' => true,
        ]);

        MasterBrandEmas::create([
            'kode_brand' => 'GALERY24',
            'nama_brand' => 'GALERY 24',
            'image_url' => '',
            'deskripsi' => '',
            'is_active' => true,
        ]);

        MasterBrandEmas::create([
            'kode_brand' => 'SAMPOERNA',
            'nama_brand' => 'SAMPOERNA',
            'image_url' => '',
            'deskripsi' => '',
            'is_active' => true,
        ]);
    }
}