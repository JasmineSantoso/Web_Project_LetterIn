<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Mock Users
        $reporters = [];
        $reporterData = [
            ['username' => 'rudi', 'name' => 'Rudi Hartono'],
            ['username' => 'ani', 'name' => 'Ani Lestari'],
            ['username' => 'budi', 'name' => 'Budi Santoso'],
            ['username' => 'citra', 'name' => 'Citra Lestari'],
            ['username' => 'dewi', 'name' => 'Dewi Sartika'],
        ];

        foreach ($reporterData as $r) {
            $reporters[] = User::firstOrCreate(
                ['username' => $r['username']],
                [
                    'fullname' => $r['name'],
                    'email' => $r['username'] . '@example.com',
                    'password' => bcrypt('password'),
                ]
            );
        }

        $reportedUsers = [];
        $reportedData = [
            ['username' => 'eko', 'name' => 'Eko Prasetyo'],
            ['username' => 'fani', 'name' => 'Fani Rahmawati'],
            ['username' => 'gina', 'name' => 'Gina Salsabila'],
            ['username' => 'hari', 'name' => 'Hari Mukti'],
            ['username' => 'irma', 'name' => 'Irma Suryani'],
        ];

        foreach ($reportedData as $u) {
            $reportedUsers[] = User::firstOrCreate(
                ['username' => $u['username']],
                [
                    'fullname' => $u['name'],
                    'email' => $u['username'] . '@example.com',
                    'password' => bcrypt('password'),
                ]
            );
        }

        // 2. Create Mock Book
        $book = Book::firstOrCreate(
            ['google_id' => 'laskar-pelangi-dummy-id'],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'cover_image' => 'cover1.jpg',
            ]
        );

        $categories = ['Spam', 'Harassment', 'Spoiler', 'Inappropriate Content', 'Hate Speech'];
        $reviewTexts = [
            'Buku ini jelek banget!! Jangan dibeli ya guys!! Pembodohan publik!! Hahaha!!',
            'SPOILER ALERT: Di akhir cerita Lintang ternyata putus sekolah dan ayahnya meninggal!',
            'Beli obat peninggi badan hubungi nomor WA 081234567890 murah kualitas terjamin!',
            'Reviewer ini orangnya bodoh banget sih, komentarnya ga mutu sama sekali, dasar payah!',
            'Isi buku ini bertentangan dengan dogma, jangan mau membaca karya orang sesat ini!',
        ];

        $reasons = [
            'Mengandung konten spam promosi produk jualan.',
            'Memberikan spoiler cerita penting tanpa sensor tag.',
            'Menghina dan melecehkan pembaca lain di kolom komentar.',
            'Menggunakan kata-kata kasar dan tidak senonoh.',
            'Menyebarkan ujaran kebencian secara terang-terangan.',
        ];

        // 3. Generate 5 Pending Reports (have active Reviews)
        for ($i = 0; $i < 5; $i++) {
            $reporter = $reporters[$i % count($reporters)];
            $reported = $reportedUsers[$i % count($reportedUsers)];
            
            $review = Review::create([
                'user_id' => $reported->user_id,
                'book_id' => $book->id,
                'rating' => rand(1, 5),
                'content' => $reviewTexts[$i % count($reviewTexts)],
                'bookshelf_status' => 'Done Read',
            ]);

            Report::create([
                'reporter_id' => $reporter->user_id,
                'reported_id' => $reported->user_id,
                'review_id' => $review->id,
                'category' => $categories[$i % count($categories)],
                'content' => $reasons[$i % count($reasons)],
                'reported_review_text' => $review->content,
                'reported_review_rating' => $review->rating,
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(rand(1, 5))->subHours(rand(1, 23)),
            ]);
        }

        // 4. Generate 5 Rejected Reports (have active Reviews)
        for ($i = 0; $i < 5; $i++) {
            $reporter = $reporters[($i + 1) % count($reporters)];
            $reported = $reportedUsers[($i + 1) % count($reportedUsers)];

            $review = Review::create([
                'user_id' => $reported->user_id,
                'book_id' => $book->id,
                'rating' => rand(3, 5),
                'content' => 'Review wajar yang dilaporkan secara keliru: buku ini bagus tapi alurnya agak lambat.',
                'bookshelf_status' => 'Done Read',
            ]);

            Report::create([
                'reporter_id' => $reporter->user_id,
                'reported_id' => $reported->user_id,
                'review_id' => $review->id,
                'category' => 'False Report',
                'content' => 'Reviewer memberikan penilaian subyektif yang tidak saya sukai.',
                'reported_review_text' => $review->content,
                'reported_review_rating' => $review->rating,
                'status' => 'rejected',
                'created_at' => Carbon::now()->subDays(rand(6, 12))->subHours(rand(1, 23)),
            ]);
        }

        // 5. Generate 40 Resolved Reports (Reviews are deleted, stored in log only)
        for ($i = 0; $i < 40; $i++) {
            $reporter = $reporters[rand(0, count($reporters) - 1)];
            $reported = $reportedUsers[rand(0, count($reportedUsers) - 1)];

            Report::create([
                'reporter_id' => $reporter->user_id,
                'reported_id' => $reported->user_id,
                'review_id' => null, // Deleted/nullified
                'category' => $categories[$i % count($categories)],
                'content' => $reasons[$i % count($reasons)],
                'reported_review_text' => $reviewTexts[$i % count($reviewTexts)] . ' [Historical Snapshotted Log]',
                'reported_review_rating' => rand(1, 2),
                'status' => 'resolved',
                'created_at' => Carbon::now()->subDays(rand(10, 30))->subHours(rand(1, 23)),
            ]);
        }
    }
}
