<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gunakan Faker bahasa Indonesia
        $faker = Faker::create('id_ID');

        // ==========================================
        // 1. USERS & ROLES
        // ==========================================

        echo "Creating Users...\n";

        // 1.1 Super Admin
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Super Admin',
            'email' => 'admin@news.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1.2 Editor Tambahan (3 Admin)
        $editors = [];
        for ($i = 0; $i < 3; $i++) {
            $editors[] = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $allAuthors = array_merge([$adminId], $editors);

        // 1.3 User Biasa (10 Orang)
        $regularUsers = [];
        for ($i = 0; $i < 10; $i++) {
            $regularUsers[] = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => $faker->unique()->freeEmail,
                'password' => Hash::make('password'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 2. KATEGORI
        // ==========================================

        echo "Creating Categories & Tags...\n";

        $categoriesList = [
            'Nasional',
            'Politik',
            'Ekonomi',
            'Olahraga',
            'Teknologi',
            'Hiburan',
            'Otomotif',
            'Kesehatan',
            'Gaya Hidup'
        ];

        $categoryIds = [];
        foreach ($categoriesList as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'created_at' => now(),
            ]);
        }

        // ==========================================
        // 3. TAGS
        // ==========================================

        $tagsList = [
            'Viral',
            'Breaking News',
            'Pilkada',
            'Timnas Indonesia',
            'Gadget Baru',
            'Tips Sehat',
            'Wisata',
            'Kuliner',
            'Review',
            'Kriminal',
            'Edukasi'
        ];

        $tagIds = [];
        foreach ($tagsList as $tag) {
            $tagIds[] = DB::table('tags')->insertGetId([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }

        // ==========================================
        // 4. BERITA (News) - 50 Item
        // ==========================================

        echo "Generating 50 News Items with Multiple Images...\n";

        for ($i = 1; $i <= 50; $i++) {
            $title = $faker->sentence(rand(6, 10));
            $status = $faker->randomElement(['published', 'published', 'published', 'draft', 'archived']);

            // --- Logic Tanggal ---
            // 1. Tentukan tanggal pembuatan (Created At) dalam 6 bulan terakhir
            $createdAt = $faker->dateTimeBetween('-6 months', 'now');

            // 2. Date Occurred (Kejadian) biasanya sebelum atau sama dengan Created At
            // Kita set kejadiannya antara 1 minggu sebelum dibuat sampai saat dibuat
            $dateOccurred = $faker->dateTimeBetween((clone $createdAt)->modify('-7 days'), $createdAt);

            // 3. Published At (Jika status published, sama dengan created_at atau sedikit sesudahnya)
            $publishedAt = ($status === 'published') ? $createdAt : null;

            // --- Counters ---
            $totalViews = $faker->numberBetween(50, 50000);
            $monthlyViews = $faker->numberBetween(0, $totalViews);
            $dailyViews = $faker->numberBetween(0, 500);

            // Insert Berita
            $newsId = DB::table('news')->insertGetId([
                'category_id' => $faker->randomElement($categoryIds),
                'author_id' => $faker->randomElement($allAuthors),
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(5),
                'excerpt' => $faker->paragraph(2),
                'content' => collect($faker->paragraphs(rand(5, 10)))->map(fn($p) => "<p>$p</p>")->implode(''),

                // Status & Flags
                'status' => $status,
                'is_headline' => $faker->boolean(15),
                'is_breaking' => $faker->boolean(5),

                // Counters
                'views_count' => $totalViews,
                'monthly_views' => $monthlyViews,
                'daily_views' => $dailyViews,

                // Timestamps
                'date_occurred' => $dateOccurred, // <--- Field Baru
                'published_at' => $publishedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // ==========================================
            // 4.1 MULTIPLE IMAGES (1 - 5 Gambar)
            // ==========================================

            $imageCount = rand(1, 5); // Tentukan jumlah gambar acak

            for ($j = 0; $j < $imageCount; $j++) {
                DB::table('news_images')->insert([
                    'news_id' => $newsId,
                    // Pastikan Anda punya dummy-1.jpg s/d dummy-10.jpg di storage/app/public/news-images/
                    'image_path' => 'news-images/dummy-' . rand(1, 5) . '.jpg',
                    'caption' => $j === 0 ? 'Ilustrasi Utama' : 'Dokumentasi tambahan ' . $j,
                    'is_primary' => ($j === 0), // Gambar pertama jadi Primary
                    'sort_order' => $j + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4.2 Tags Pivot
            $randomTags = $faker->randomElements($tagIds, rand(1, 3));
            foreach ($randomTags as $tId) {
                DB::table('news_tags')->insert([
                    'news_id' => $newsId,
                    'tag_id' => $tId
                ]);
            }

            // 4.3 Komentar
            if ($status === 'published' && $faker->boolean(60)) {
                $commentCount = rand(1, 5);
                for ($k = 0; $k < $commentCount; $k++) {
                    DB::table('comments')->insert([
                        'news_id' => $newsId,
                        'user_id' => $faker->randomElement($regularUsers),
                        'content' => $faker->sentence(rand(5, 15)),
                        'is_approved' => $faker->boolean(90),
                        'created_at' => $faker->dateTimeBetween($publishedAt, 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        echo "Seeding Complete! Login with: admin@news.test / password\n";
    }
}
