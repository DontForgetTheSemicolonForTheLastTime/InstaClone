<?php

use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\FollowsController;
use App\Mail\NewUserWelcomeMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/email', function () {
    return new NewUserWelcomeMail();
});

Route::get('/', [PostsController::class, 'index']);

Route::get('/p/create', [PostsController::class, 'create']);

// Show the respective image clicked on the user profile
Route::get('/p/{post}', [PostsController::class, 'show']);

Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');

// Store the post content
Route::post('/p', [PostsController::class, 'store']);

// Show respective user profile edit page
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit']);

// Action route for when we update the user profile 
Route::patch('/profile/{user}', [ProfilesController::class, 'update']);


Route::post('/follow/{user}', [FollowsController::class, 'store']);

