<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table with the 8 product categories.
     *
     * Uses firstOrCreate so the seeder is safe to run multiple times:
     * - On first run: inserts all 8 categories.
     * - On subsequent runs: no duplicates, no changes to existing rows.
     *
     * Descriptions are brief and informative for each category.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Smartphone',
                'description' => 'Ponsel pintar dengan konektivitas seluler dan berbagai fitur canggih.',
            ],
            [
                'name'        => 'Laptop',
                'description' => 'Komputer portabel untuk produktivitas, desain, dan gaming.',
            ],
            [
                'name'        => 'Headphone',
                'description' => 'Perangkat audio over-ear dan in-ear berkualitas tinggi.',
            ],
            [
                'name'        => 'Tablet',
                'description' => 'Perangkat layar sentuh portabel antara smartphone dan laptop.',
            ],
            [
                'name'        => 'Accessories',
                'description' => 'Aksesori komputer dan perangkat elektronik pendukung.',
            ],
            [
                'name'        => 'Camera',
                'description' => 'Kamera digital mirrorless, DSLR, dan aksesoris fotografi.',
            ],
            [
                'name'        => 'Gaming Console',
                'description' => 'Konsol permainan video untuk hiburan di rumah maupun portabel.',
            ],
            [
                'name'        => 'Monitor',
                'description' => 'Layar monitor untuk komputer, gaming, dan produktivitas profesional.',
            ],
        ];

        foreach ($categories as $data) {
            // firstOrCreate: hanya membuat baris baru jika nama belum ada.
            // Tidak meneruskan default values agar ID dan name tidak pernah diubah.
            $category = Category::firstOrCreate(
                ['name' => $data['name']]
            );

            // Isi description HANYA jika masih NULL atau string kosong.
            // Tidak menimpa description yang sudah berisi nilai.
            // Tidak menyentuh tabel products atau category_id.
            if (empty($category->description)) {
                $category->update(['description' => $data['description']]);
            }
        }
    }
}
