<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['google_id', 'title', 'author', 'cover_image'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookshelves()
    {
        return $this->belongsToMany(Bookshelf::class, 'bookshelf_book', 'book_id', 'bookshelf_id')->withTimestamps();
    }
}
