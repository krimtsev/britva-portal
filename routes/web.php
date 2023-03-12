<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\BlogController;
use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\Sheet\PageSheetController;

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
    return view('welcome');
});

Route::get('/radio', function () {
    return view('radio');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [BlogController::class, '__invoke'])->name('post.index');

    Route::get('page/{slug}', [PageController::class, '__invoke']);
    Route::get('sheet/{slug}', [PageSheetController::class, '__invoke']);
});

require __DIR__ . '/auth.php';

require __DIR__ . '/static.php';

require __DIR__ . '/dashboard/index.php';
require __DIR__ . '/dashboard/posts.php';
require __DIR__ . '/dashboard/users.php';
require __DIR__ . '/dashboard/pages.php';
require __DIR__ . '/dashboard/sheets.php';
