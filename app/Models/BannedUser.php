<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedUser extends Model
{
    protected $table = 'banned_users';

    protected $fillable = [
        'user_id',
        'username',
        'fullname',
        'email',
        'following_count',
        'followers_count',
        'reviews_count',
        'bookshelves_count',
        'registered_at',
        'last_login_at',
        'banned_at',
        'ban_reason',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'last_login_at' => 'datetime',
        'banned_at' => 'datetime',
    ];
}
