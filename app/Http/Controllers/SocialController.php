<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Follow;

class SocialController extends Controller
{
    public function notifications()
    {
        return view('social.notifications');
    }

    public function bookmates(Request $request)
    {
        $search = $request->query('q');
        $users = [];

        if ($search) {
            $users = User::where('username', 'like', '%' . $search . '%')
                ->orWhere('fullname', 'like', '%' . $search . '%')
                ->get();
        }

        return view('social.bookmates', compact('users', 'search'));
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
