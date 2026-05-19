<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Laut Bercerita',
            'author' => 'Leila S. Chudori',
            'cover_image' => 'http://books.google.com/books/content?id=e-ZDDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'
        ]);
    }
}
