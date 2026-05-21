<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $favoriteBooks = $user->favoriteBooks()->get();
        $totalReviews = \App\Models\Review::where('user_id', $user->user_id)->count();
        $totalBooks = $favoriteBooks->count();
        
        $userReviews = \App\Models\Review::where('user_id', $user->user_id)
            ->with('book')
            ->latest()
            ->get();

        // Get following users (User objects) for bookmates section
        $followingIds = $user->following()->pluck('following_id');
        $bookmates = \App\Models\User::whereIn('user_id', $followingIds)->get();

        // Get user's bookshelves with their books
        $bookshelves = $user->bookshelves()->with('books')->get();

        return view('profile.index', compact('favoriteBooks', 'totalReviews', 'totalBooks', 'userReviews', 'bookmates', 'bookshelves'));
    }

    public function show($username)
    {
        $user = \App\Models\User::where('username', $username)->firstOrFail();

        // Check if viewing own profile
        if (auth()->check() && auth()->user()->username === $username) {
            return redirect()->route('profile');
        }

        $isFollowing = false;
        if (auth()->check()) {
            $isFollowing = \App\Models\Follow::where('follower_id', auth()->id())
                ->where('following_id', $user->user_id)
                ->exists();
        }

        $favoriteBooks = $user->favoriteBooks()->get();
        $totalReviews = \App\Models\Review::where('user_id', $user->user_id)->count();
        $totalBooks = $favoriteBooks->count();
        
        $userReviews = \App\Models\Review::where('user_id', $user->user_id)
            ->with('book')
            ->latest()
            ->get();

        // Get following users (User objects) for bookmates section
        $followingIds = $user->following()->pluck('following_id');
        $bookmates = \App\Models\User::whereIn('user_id', $followingIds)->get();

        return view('profile.show', compact('user', 'isFollowing', 'favoriteBooks', 'totalReviews', 'totalBooks', 'userReviews', 'bookmates'));
    }

    public function settings()
    {
        return view('profile.settings');
    }

    public function friendsProfile($id)
    {
        return view('profile.friend', compact('id'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'bio'      => 'nullable|string',
            'profile'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user->fullname = $request->fullname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/profiles'), $filename);
            
            // Delete old profile picture if exists and not default
            if ($user->profile && file_exists(public_path('images/' . $user->profile))) {
                @unlink(public_path('images/' . $user->profile));
            }

            $user->profile = 'profiles/' . $filename;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
