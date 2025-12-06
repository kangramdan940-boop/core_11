<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterHomeSlider;

class MasterMenuSeeder extends Seeder
{
    public function run(): void
    {
        MasterHomeSlider::create([
            'image_url' => 'front/images/sliders/slider-1.png',
            'title' => 'Promo Akhir Tahun',
            'description' => 'Diskon hingga 30% untuk semua produk emas.',
        ]);

        MasterHomeSlider::create([
            'image_url' => 'front/images/sliders/slider-2.png',
            'title' => 'Cicilan Emas',
            'description' => 'Beli emas sekarang, bayar kemudian dengan cicilan ringan.',
        ]);

        MasterHomeSlider::create([
            'image_url' => 'front/images/sliders/slider-3.png',
            'title' => 'Katalog Terbaru',
            'description' => 'Lihat koleksi cincin, gelang, dan kalung terbaru.',
        ]);
    }
}