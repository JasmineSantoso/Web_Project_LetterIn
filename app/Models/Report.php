<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reported_id',
        'review_id',
        'category',
        'content',
        'reported_review_text',
        'reported_review_rating',
        'status', // pending, resolved, rejected
    ];

    /**
     * Get the user who reported the content.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    /**
     * Get the user whose content was reported.
     */
    public function reported()
    {
        return $this->belongsTo(User::class, 'reported_id', 'user_id');
    }

    /**
     * Get the reported review.
     */
    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
