<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Follow;
use App\Models\Review;
use App\Models\Book;
use App\Models\BannedUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or get Book for mock reviews
        $book = Book::firstOrCreate(
            ['google_id' => 'laskar-pelangi-dummy-id'],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'cover_image' => 'cover1.jpg',
            ]
        );

        // 2. Seed active users
        // Seed the exact user from the sketch
        $sketchUser = User::firstOrCreate(
            ['username' => 'username'],
            [
                'fullname' => 'full name',
                'email' => 'test@gmail.com',
                'password' => Hash::make('password123'),
                'created_at' => Carbon::create(2011, 7, 25, 12, 0, 0),
                'last_login_at' => null,
            ]
        );

        $jasmine = User::firstOrCreate(
            ['username' => 'jasmine'],
            [
                'fullname' => 'Jasmine Aulia',
                'email' => 'jasmine@example.com',
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now()->subMonths(6),
                'last_login_at' => Carbon::now()->subDays(1),
            ]
        );

        $kadiva = User::firstOrCreate(
            ['username' => 'kadiva'],
            [
                'fullname' => 'Kadiva Wardani',
                'email' => 'kadiva@example.com',
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now()->subMonths(5),
                'last_login_at' => Carbon::now()->subDays(2),
            ]
        );

        $azalea = User::firstOrCreate(
            ['username' => 'azalea'],
            [
                'fullname' => 'Azalea Shafa',
                'email' => 'azalea@example.com',
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now()->subMonths(4),
                'last_login_at' => Carbon::now()->subHours(5),
            ]
        );

        $samara = User::firstOrCreate(
            ['username' => 'samara'],
            [
                'fullname' => 'Samara Lestari',
                'email' => 'samara@example.com',
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now()->subMonths(3),
                'last_login_at' => Carbon::now()->subMinutes(30),
            ]
        );

        // 3. Create followers/following for sketchUser (following: 2, followers: 4)
        // Followers: jasmine, kadiva, azalea, samara -> sketchUser
        foreach ([$jasmine, $kadiva, $azalea, $samara] as $follower) {
            Follow::firstOrCreate([
                'follower_id' => $follower->user_id,
                'following_id' => $sketchUser->user_id,
            ]);
        }
        // Following: sketchUser -> jasmine, kadiva
        foreach ([$jasmine, $kadiva] as $following) {
            Follow::firstOrCreate([
                'follower_id' => $sketchUser->user_id,
                'following_id' => $following->user_id,
            ]);
        }

        // 4. Create 6 reviews for sketchUser (total reviews: 6, bookshelves: 0)
        // Ensure bookshelf_status is null so bookshelf count is 0
        for ($i = 1; $i <= 6; $i++) {
            Review::create([
                'user_id' => $sketchUser->user_id,
                'book_id' => $book->id,
                'rating' => rand(3, 5),
                'content' => "Review ke-{$i} oleh user sketsa. Buku ini sangat berkesan dan memiliki pesan moral yang mendalam.",
                'bookshelf_status' => null, // null means not on bookshelf
            ]);
        }

        // Create some bookshelf items (reviews with bookshelf_status) for other users
        Review::create([
            'user_id' => $samara->user_id,
            'book_id' => $book->id,
            'rating' => 5,
            'content' => 'Buku fantasi yang sangat seru!',
            'bookshelf_status' => 'Reading',
        ]);
        Review::create([
            'user_id' => $samara->user_id,
            'book_id' => $book->id,
            'rating' => 4,
            'content' => 'Alur ceritanya menarik dan tidak membosankan.',
            'bookshelf_status' => 'Done Read',
        ]);

        // 5. Seed some banned users
        BannedUser::firstOrCreate(
            ['email' => 'banned_user1@example.com'],
            [
                'user_id' => 999,
                'username' => 'toxic_spammer',
                'fullname' => 'Toxic Spammer',
                'following_count' => 12,
                'followers_count' => 1,
                'reviews_count' => 15,
                'bookshelves_count' => 3,
                'registered_at' => Carbon::now()->subYears(2),
                'last_login_at' => Carbon::now()->subDays(5),
                'banned_at' => Carbon::now()->subDays(2),
                'ban_reason' => 'Spamming inappropriate comments and links in reviews.',
            ]
        );

        BannedUser::firstOrCreate(
            ['email' => 'hacker_dude@example.com'],
            [
                'user_id' => 1000,
                'username' => 'exploit_finder',
                'fullname' => 'Exploit Finder',
                'following_count' => 0,
                'followers_count' => 0,
                'reviews_count' => 0,
                'bookshelves_count' => 0,
                'registered_at' => Carbon::now()->subMonths(1),
                'last_login_at' => Carbon::now()->subDays(10),
                'banned_at' => Carbon::now()->subDays(9),
                'ban_reason' => 'Attempting to inject malicious scripts into review text fields.',
            ]
        );
    }
}
