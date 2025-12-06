<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterGramasiEmas;

class MasterGramasiEmasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => '0.5 Gram',
                'gramasi' => 0.500,
                'is_active' => true,
                'catatan' => '',
            ],
            [
                'nama' => '1 Gram',
                'gramasi' => 1.000,
                'is_active' => true,
                'catatan' => '',
            ],
            [
                'nama' => '2 Gram',
                'gramasi' => 2.000,
                'is_active' => true,
                'catatan' => '',
            ],
            [
                'nama' => '3 Gram',
                'gramasi' => 3.000,
                'is_active' => true,
                'catatan' => '',
            ],
            [
                'nama' => '5 Gram',
                'gramasi' => 5.000,
                'is_active' => true,
                'catatan' => '',
            ],
            [
                'nama' => '10 Gram',
                'gramasi' => 10.000,
                'is_active' => true,
                'catatan' => '',
            ],
 [
                'nama' => '25 Gram',
                'gramasi' => 25.000,
                'is_active' => true,
                'catatan' => '',
            ],
 [
                'nama' => '50 Gram',
                'gramasi' =>50.000,
                'is_active' => true,
                'catatan' => '',
            ],
 [
                'nama' => '100 Gram',
                'gramasi' => 100.000,
                'is_active' => true,
                'catatan' => '',
            ],
        ];

        foreach ($data as $row) {
            MasterGramasiEmas::create($row);
        }
    }
}