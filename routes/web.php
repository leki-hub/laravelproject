<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

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
// all listings
Route::get('/',[ListingController::class, 'index']);


// create listing
Route::get('/listing/create', [ListingController::class, 'create'])->middleware('auth');

// store listing
Route::post('/listing', [ListingController::class, 'store'])->middleware('auth');


// show edit form 
Route::get('/listing/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// update edited  listing
Route::put('/listing/{listing}', [ListingController::class, 'update'])->middleware('auth');
//delete listing item
Route::delete('/listing/{listing}', [ListingController::class, 'destroy'])->middleware('auth');
//manage listings
Route::get('/listing/manage', [ListingController::class, 'manage'])->middleware('auth');

// single listing
Route::get('/listing/{id}', [ListingController::class, 'show']);


// register user form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');
// create user
Route::post('/users', [UserController::class, 'store']);

//logout user
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
//login user form
Route::get('/user/login', [UserController::class, 'login'])->name('login')->middleware('guest');
//login user
Route::post('/user/authenticate', [UserController::class, 'authenticate']);
