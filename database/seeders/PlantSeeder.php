<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Plant;
use Illuminate\Support\Str;

class PlantSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'category' => 'rimpang-umbi',
                'name' => 'Jahe Merah',
                'latin_name' => 'Zingiber officinale var. rubrum',
                'family' => 'ZINGIBERACEAE',
                'description_short' => 'Kaya senyawa gingerol dan shogaol berkhasiat sebagai imunomodulator kuat, anti-inflamasi, pereda nyeri sendiri, serta...',
            ],
            [
                'category' => 'rimpang-umbi',
                'name' => 'Temulawak',
                'latin_name' => 'Curcuma xanthorrhiza',
                'family' => 'ZINGIBERACEAE',
                'description_short' => 'Herba unggulan Indonesia dengan kandungan kurkuminoid dan xanthorrhizol tinggi untuk hepatoprotektor (pelindung...',
            ],
            [
                'category' => 'daun-herba',
                'name' => 'Sambiloto',
                'latin_name' => 'Andrographis paniculata',
                'family' => 'ACANTHACEAE',
                'description_short' => 'Raja herba pahit nusantara. Mengandung senyawa aktif andrografoid yang terbukti klinis sebagai antivirus, penurun demam,...',
            ],
            [
                'category' => 'daun-herba',
                'name' => 'Pegagan',
                'latin_name' => 'Centella asiatica',
                'family' => 'APIACEAE',
                'description_short' => 'Herba nootropik alami pendukung daya ingat dan fokus otak. Mengandung asiatikosida yang mempercepat...',
            ],
            [
                'category' => 'rimpang-umbi',
                'name' => 'Kunyit',
                'latin_name' => 'Curcuma zedoaria',
                'family' => 'ZINGIBERACEAE',
                'description_short' => 'Mengandung Ribosome Inactivating Proteins (RIPs) dan minyak atsiri bernilai farmasi tinggi yang diteliti sebagai agen...',
            ],
            [
                'category' => 'daun-herba',
                'name' => 'Daun Kelor',
                'latin_name' => 'Moringa oleifera',
                'family' => 'MORINGACEAE',
                'description_short' => 'Superfood kebanggaan dunia dengan kandungan vitamin C, asam amino esensial, kalsium, dan kuresetin. Efektif...',
            ],
            [
                'category' => 'daun-herba',
                'name' => 'Kumis Kucing',
                'latin_name' => 'Orthosiphon aristatus',
                'family' => 'LAMIACEAE',
                'description_short' => 'Diuretik alami teruji untuk meluruhkan batu ginjal, meredakan infeksi kandungan kemia, dan menstabilkan tekanan darah...',
            ],
            [
                'category' => 'daun-herba',
                'name' => 'Sirih Merah',
                'latin_name' => 'Piper crocatum',
                'family' => 'PIPERACEAE',
                'description_short' => 'Antiseptik dan antibakteri spektrum luas. Efektif untuk terapi komplementer diabetes melitus, pembersih antiseptik...',
            ],
            [
                'category' => 'kayu-kulit',
                'name' => 'Kayu Manis',
                'latin_name' => 'Cinnamomum burmannii',
                'family' => 'LAURACEAE',
                'description_short' => 'Kandungan sinamaldehyde dan eugenol tinggi berguna memperbaiki sensitivitas insulin, menurunkan resistensi glukosa,...',
            ],
            [
                'category' => 'daun-herba',
                'name' => 'Meniran Hijau',
                'latin_name' => 'Phyllanthus niruri',
                'family' => 'PHYLLANTHACEAE',
                'description_short' => 'Herba imunomodulator teruji klinis yang mengaktivasi proliferasi limfosit T dan fagositosis makrofag, sekaligus...',
            ],
            [
                'category' => 'rimpang-umbi',
                'name' => 'Bangle',
                'latin_name' => 'Zingiber cassumunar',
                'family' => 'ZINGIBERACEAE',
                'description_short' => 'Rimpang beraroma khas pedas hangat. Memiliki bioaktivitas anti-asma (bronkodilator alami), pereda kejang otot...',
            ],
            [
                'category' => 'kayu-kulit',
                'name' => 'Pasak Bumi',
                'latin_name' => 'Eurycoma longifolia',
                'family' => 'SIMAROUBACEAE',
                'description_short' => 'Pohon obat legendaris nusantara berkhasiat ergogenik meningkatkan kadar testosteron bebas, ketahanan fisik...',
            ],
        ];

        foreach ($data as $item) {
            $category = Category::where('slug', $item['category'])->first();

            if (!$category) {
                continue; // skip kalau kategori tidak ditemukan
            }

            Plant::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'latin_name' => $item['latin_name'],
                    'family' => $item['family'],
                    'description_short' => $item['description_short'],
                    'description_full' => $item['description_short'], // sementara, nanti bisa diisi lengkap
                    'is_active' => true,
                ]
            );
        }
    }
}