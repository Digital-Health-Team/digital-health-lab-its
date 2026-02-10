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
        // Gunakan Faker bahasa Indonesia agar data terlihat nyata
        $faker = Faker::create('id_ID');

        // ==========================================
        // 1. USERS & ROLES
        // ==========================================

        echo "Creating Users...\n";

        // 1.1 Super Admin (Untuk Anda Login)
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Super Admin',
            'email' => 'admin@news.test', // Login pakai ini
            'password' => Hash::make('password'), // Password: password
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1.2 Editor Tambahan (3 Admin lain sebagai penulis)
        $editors = [];
        for ($i = 0; $i < 3; $i++) {
            $editors[] = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => Hash::make('password'),
                'role' => 'admin', // Role admin agar bisa akses dashboard
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Gabungkan admin utama dengan editor untuk random author
        $allAuthors = array_merge([$adminId], $editors);

        // 1.3 User Biasa (10 Orang untuk Komentar)
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
        // 2. KATEGORI (Categories)
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
            'Review'
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

        echo "Generating 50 News Items...\n";

        for ($i = 1; $i <= 50; $i++) {
            $title = $faker->sentence(rand(6, 10)); // Judul 6-10 kata
            $status = $faker->randomElement(['published', 'published', 'published', 'draft', 'archived']); // Lebih banyak published

            // Random Views untuk Chart Statistik
            $totalViews = $faker->numberBetween(50, 50000);
            $monthlyViews = $faker->numberBetween(0, $totalViews); // Pastikan monthly <= total
            $dailyViews = $faker->numberBetween(0, 500);

            // Tanggal Publish
            $publishDate = ($status === 'published') ? $faker->dateTimeBetween('-6 months', 'now') : null;

            // Insert Berita
            $newsId = DB::table('news')->insertGetId([
                'category_id' => $faker->randomElement($categoryIds),
                'author_id' => $faker->randomElement($allAuthors),
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(5),
                'excerpt' => $faker->paragraph(2), // Ringkasan pendek
                'content' => collect($faker->paragraphs(rand(5, 10)))->map(fn($p) => "<p>$p</p>")->implode(''), // Konten HTML

                // Status Flags
                'status' => $status,
                'is_headline' => $faker->boolean(15), // 15% Chance Headline
                'is_breaking' => $faker->boolean(5),  // 5% Chance Breaking News

                // Counters
                'views_count' => $totalViews,
                'monthly_views' => $monthlyViews,
                'daily_views' => $dailyViews,

                'published_at' => $publishDate,
                'created_at' => $publishDate ?? now(),
                'updated_at' => now(),
            ]);

            // 4.1 Gambar Berita (Placeholder)
            // Kita gunakan path dummy. Pastikan nanti di view ada logic:
            // jika file tidak ada di storage, tampilkan placeholder url.
            DB::table('news_images')->insert([
                'news_id' => $newsId,
                'image_path' => 'news-images/dummy-' . rand(1, 5) . '.jpg',
                'caption' => 'Ilustrasi: ' . substr($title, 0, 20) . '...',
                'is_primary' => true,
                'sort_order' => 1
            ]);

            // 4.2 Tags Pivot (1 Berita punya 1-3 Tag)
            $randomTags = $faker->randomElements($tagIds, rand(1, 3));
            foreach ($randomTags as $tId) {
                DB::table('news_tags')->insert([
                    'news_id' => $newsId,
                    'tag_id' => $tId
                ]);
            }

            // 4.3 Komentar (Hanya untuk berita published)
            if ($status === 'published' && $faker->boolean(60)) {
                $commentCount = rand(1, 5);
                for ($k = 0; $k < $commentCount; $k++) {
                    DB::table('comments')->insert([
                        'news_id' => $newsId,
                        'user_id' => $faker->randomElement($regularUsers),
                        'content' => $faker->sentence(rand(5, 15)),
                        'is_approved' => $faker->boolean(90), // 90% Approved
                        'created_at' => $faker->dateTimeBetween($publishDate, 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        echo "Seeding Complete! Login with: admin@news.test / password\n";
    }
}
