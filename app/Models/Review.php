<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'content',
        'songs',
        'bookshelf_status'
    ];

    protected $casts = [
        'songs' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class, 'review_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(ReviewComment::class, 'review_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(ReviewReport::class, 'review_id', 'id');
    }
}
