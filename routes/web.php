<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RealtorListingController;
use App\Http\Controllers\RealtorListingImageController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[IndexController::class,'index']);

Route::get('/show',[IndexController::class, 'show'])->middleware('auth');


Route::resource('listing', ListingController::class)->only(['index', 'show'])
->middleware('auth');

Route::get('login', [AuthController::class, 'create'])->name('login');
Route::post('login', [AuthController::class, 'store'])->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');

Route::resource('user-account',UserAccountController::class)->only(['create','store']);

Route::prefix('realtor')
  ->name('realtor.')
  ->middleware('auth')
  ->group(function () {
    Route::name('listing.restore')
    ->put(
      'listing/{listing}/restore',
      [RealtorListingController::class, 'restore']
    )->withTrashed();
    Route::resource('listing', RealtorListingController::class)
    ->only(['index', 'destroy', 'edit', 'update', 'create', 'store'])
    ->withTrashed();

  Route::resource('listing.image', RealtorListingImageController::class)->only(['create', 'store', 'destroy']);

  });



  Route::post('/listing/{listing}/like',[LikeController::class, 'toggle']);
  Route::delete('/listing/{listing}/like',[LikeController::class , 'checkLikes']);

  Route::resource('listing/{listing}/favorite', FavoriteController::class)->only('index');

  Route::get('/chat', function(){
    return Inertia::render('Chat/Container');
  });
  Route::get('/chat/rooms', [ChatController::class, 'rooms']);
  Route::get('/chat/room/{roomId}/messages', [ChatController::class, 'messages']);
  Route::post('/chat/room/{roomId}/message', [ChatController::class, 'newMessage']);
