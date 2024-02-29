<?php

use App\Http\Controllers\ChatsController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\SearchController;

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

Route::get('/', function () {
    return Inertia::render('Accueil');
});

Route::get('/dashboard', [BoutiqueController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('shop', BoutiqueController::class)->except('update')->middleware('seller');
    Route::post('shop/update', [BoutiqueController::class, 'update'])->middleware('seller');
    Route::resource('article', ArticleController::class);
    Route::post('article/update', [ArticleController::class, 'update']);
    Route::get('/preferences', [UserController::class, 'preference']);
    Route::post('/preferences', [UserController::class, 'addPref']);
    Route::get('/getBoutiques', [BoutiqueController::class, 'getBoutiques']);
    Route::post('plusRecent', [BoutiqueController::class, 'plusRecent']);
    Route::post('tendance', [BoutiqueController::class, 'tendance']);
    Route::get('visit/shop/{name}',[BoutiqueController::class, 'visit']);
    Route::post('article/commande', [ArticleController::class, 'commande']);
    Route::get('allArticles', [ArticleController::class, 'allArticles']);
    Route::get('myArticles', [ArticleController::class, 'myArticles']);
    Route::get('view/article/{id}',[ArticleController::class, 'showArticle']);
    Route::get('contact-us', [UserController::class, 'contact'])->name('contact');
    Route::get('moreA/{num}', [ArticleController::class, 'moreA']);
    Route::get('moreD/{num}', [ArticleController::class, 'moreD']);
    Route::get('search', [SearchController::class, 'index'])->name('search');
    Route::post('searchBoutResult', [SearchController::class, 'searchBoutique']);
    Route::post('searchArtResult', [SearchController::class, 'searchArticle']);
    // Route::get('chat/{id}', [ChatsController::class, 'showChats']);
    // Route::get('chats/boutique/{id}', [ChatsController::class, 'showChatsBoutique']);
    // Route::post('chat', [ChatsController::class, 'sendChat']);
    // Route::post('boutSend', [ChatsController::class, 'boutiqueSend']);
    // Route::get('chat/boutique/{id}', [ChatsController::class, 'getBoutiqueConvs']);
});
Route::post('getMail', [UserController::class, 'getMail']);

// Route::get('/', [BoutiqueController::class, 'acceuil']);
// Route::post('user', [UserController::class, 'store']);
// Route::post('login', [UserController::class, 'login']);
// Route::get('articles', [ArticleController::class, 'index']);
// Route::get('acceuil', [BoutiqueController::class, 'acceuil']);

require __DIR__.'/auth.php';
