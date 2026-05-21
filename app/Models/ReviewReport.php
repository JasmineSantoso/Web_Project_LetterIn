<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReport extends Model
{
    protected $table = 'review_reports';

    protected $fillable = [
        'user_id',
        'review_id',
        'reason',
        'details',
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
