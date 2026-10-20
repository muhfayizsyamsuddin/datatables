<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Langkah-langkah (order matters):
     *
     * 1. Tambahkan kolom `category_id` (nullable) ke tabel `products`.
     *    - Nullable agar 12 baris produk yang sudah ada tidak rusak saat
     *      kolom ditambahkan (nilai default NULL sementara).
     *    - Tipe `unsignedBigInteger` kompatibel dengan PK `categories.id`.
     *
     * 2. Migrasikan kategori unik dari `products.category` ke tabel
     *    `categories` menggunakan INSERT IGNORE (aman dijalankan berulang).
     *
     * 3. Perbarui `products.category_id` berdasarkan kecocokan
     *    `products.category = categories.name` agar setiap produk lama
     *    terhubung ke kategori yang benar tanpa mengubah kolom `category`.
     *
     * 4. Tambahkan foreign key RESTRICT (mencegah penghapusan kategori yang
     *    masih dipakai produk) setelah semua baris terisi.
     *
     * Kolom `products.category` (teks lama) TETAP dipertahankan selama masa
     * transisi; tidak diubah maupun dihapus oleh migration ini.
     */
    public function up(): void
    {
        // ── 1. Tambahkan kolom category_id (nullable, tanpa FK dulu) ──────
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
        });

        // ── 2. Pindahkan kategori unik ke tabel categories ────────────────
        //    INSERT IGNORE memastikan tidak duplikat jika migration dijalankan
        //    ulang setelah rollback parsial.
        DB::statement("
            INSERT IGNORE INTO categories (name, created_at, updated_at)
            SELECT DISTINCT category, NOW(), NOW()
            FROM products
            WHERE category IS NOT NULL AND category <> ''
        ");

        // ── 3. Isi category_id di setiap baris produk ─────────────────────
        //    JOIN berdasarkan nama kategori; kolom `category` lama tidak diubah.
        DB::statement("
            UPDATE products p
            JOIN categories c ON c.name = p.category
            SET p.category_id = c.id
            WHERE p.category IS NOT NULL AND p.category <> ''
        ");

        // ── 4. Pasang foreign key RESTRICT setelah data terisi ───────────
        //    RESTRICT (default MySQL) mencegah DELETE kategori yang masih
        //    direferensikan oleh produk → lebih aman dari nullOnDelete.
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Menghapus foreign key dan kolom category_id. Kolom `products.category`
     * (teks lama) tidak disentuh — data produk tetap utuh setelah rollback.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
