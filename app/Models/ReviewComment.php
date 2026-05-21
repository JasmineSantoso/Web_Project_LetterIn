<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    protected $table = 'review_comments';

    protected $fillable = [
        'user_id',
        'review_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id', 'id');
    }
}
