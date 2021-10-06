<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        // Caching the: Posts | Followers | Following 
        $postCount = Cache::remember('count.posts.' . $user->id, now()->addSeconds(30), function () use ($user) {
            return $user->posts->count();
        });;

        $followerCount =  Cache::remember('follower.posts.' . $user->id, now()->addSeconds(30), function () use ($user) {
            return $user->profile->followers->count();
        });

        $followingCount = Cache::remember('following.posts.' . $user->id, now()->addSeconds(30), function () use ($user) {
            return $user->following->count();
        });


        return view('profiles.index', compact('user', 'follows', 'postCount', 'followerCount', 'followingCount'));
    }

    public function edit(User $user) {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user) {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            // This is to make all the images the same size.
            // Using an external lib called intervention\image
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        // using auth() gives one more layear of protection
        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? [],
        ));

        return redirect("/profile/{$user->id}");
    }
}
