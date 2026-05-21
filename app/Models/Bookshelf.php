<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookshelf extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'bookshelf_book', 'bookshelf_id', 'book_id')->withTimestamps();
    }
}
