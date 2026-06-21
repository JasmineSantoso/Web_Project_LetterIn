<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Follow;
use App\Models\Review;

class SocialController extends Controller
{
    public function notifications()
    {
        $user = auth()->user();

        // 1. Fetch followers
        $followers = Follow::where('following_id', $user->user_id)
            ->with('follower')
            ->get()
            ->map(function ($follow) {
                return (object)[
                    'type' => 'follow',
                    'user' => $follow->follower,
                    'created_at' => $follow->created_at,
                    'follow_id' => $follow->id,
                ];
            });

        // 2. Fetch custom notifications
        $customNotifs = \App\Models\Notification::where('user_id', $user->user_id)
            ->get()
            ->map(function ($notif) {
                return (object)[
                    'type' => $notif->type,
                    'data' => $notif->data,
                    'created_at' => $notif->created_at,
                    'notif_id' => $notif->id,
                ];
            });

        // 3. Merge and sort
        $notifications = $followers->concat($customNotifs)->sortByDesc('created_at');

        return view('social.notifications', compact('notifications'));
    }

    public function bookmates(Request $request)
    {
        $search = $request->query('q');
        $tab = $request->query('tab', 'all');
        $users = [];
        $reviews = collect();
        $friends = collect();
        $similarUsers = collect();
        $isFriendFeed = false;

        if ($search) {
            $users = User::where(function($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('fullname', 'like', '%' . $search . '%');
            })
            ->where('is_admin', false)
            ->get();
        } else {
            // Get people the authenticated user is following
            $followingIds = Follow::where('follower_id', auth()->id())->pluck('following_id');

            if ($tab === 'friends') {
                // Fetch reviews from followed users (friends) only
                $reviews = Review::whereIn('user_id', $followingIds)
                    ->with(['user', 'book', 'likes', 'comments'])
                    ->latest()
                    ->get();
            } elseif ($tab === 'similar') {
                // Recommend users they are NOT following, NOT themselves, and NOT admin
                $similarUsers = User::where('user_id', '!=', auth()->id())
                    ->where('is_admin', false)
                    ->whereNotIn('user_id', $followingIds)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();
            } else {
                // Fetch ALL reviews from ALL users
                $reviews = Review::with(['user', 'book', 'likes', 'comments'])
                    ->latest()
                    ->get();
            }
        }

        return view('social.bookmates', compact('users', 'search', 'reviews', 'friends', 'similarUsers', 'tab', 'isFriendFeed'));
    }

    public function toggleFollow(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);

        $followerId = auth()->id();
        $followingId = $request->user_id;

        if ($followerId == $followingId) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }

        $follow = Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->first();

        if ($follow) {
            $follow->delete();
            return response()->json(['status' => 'unfollowed']);
        } else {
            Follow::create([
                'follower_id' => $followerId,
                'following_id' => $followingId,
            ]);
            return response()->json(['status' => 'followed']);
        }
    }
}
