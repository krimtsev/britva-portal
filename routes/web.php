<?php

use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\Post\BlogController;
use App\Http\Controllers\Sheet\PageSheetController;
use App\Http\Controllers\Upload\UploadController;
use App\Http\Controllers\Upload\UploadFilesController;
use App\Http\Controllers\Tickets\TicketsController;
use App\Http\Controllers\Tickets\TicketsFilesController;
use Illuminate\Support\Facades\Route;


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
    Route::get('/', [BlogController::class, '__invoke'])
        ->name('post.index');

    Route::get('/page/{slug}', [PageController::class, '__invoke'])
        ->name('page.index');

    Route::get('/sheet/{slug}', [PageSheetController::class, '__invoke'])
        ->name('sheet.index');

    // Публичный метод для облака файлов
    Route::get('/cloud/{slug?}', [UploadController::class, 'show'])
        ->name('upload.cloud');

    Route::get('/download/{folder}/{file}', [UploadFilesController::class, 'download'])
        ->name('upload.download');

    // Публичный метод для заявок
    Route::get('/download-ticket/{folder}/{file}', [TicketsFilesController::class, 'download'])
        ->name('ticket.download');
});

require __DIR__ . '/auth.php';

require __DIR__ . '/static.php';

/**
 * Панель системиного администратора
 */
Route::group(['middleware' => ['auth', 'isAdminOrSysAdmin'], 'prefix' => 'dashboard', 'view' => 'dashboard' ], function () {
    require __DIR__ . '/dashboard/index.php';
    require __DIR__ . '/dashboard/posts.php';
    require __DIR__ . '/dashboard/pages.php';
    require __DIR__ . '/dashboard/analytics.php';
    require __DIR__ . '/dashboard/royalty.php';
    require __DIR__ . '/dashboard/partners.php';
    require __DIR__ . '/dashboard/upload.php';
    require __DIR__ . '/dashboard/tickets.php';
});

Route::group(['middleware' => ['auth', 'isSysAdmin'], 'prefix' => 'dashboard', 'view' => 'dashboard' ], function () {
    require __DIR__ . '/dashboard/users.php';
    require __DIR__ . '/dashboard/sheets.php';
    require __DIR__ . '/dashboard/digests.php';
    require __DIR__ . '/dashboard/jobs.php';
    require __DIR__ . '/dashboard/mango.php';
    require __DIR__ . '/dashboard/missed-calls.php';
    require __DIR__ . '/dashboard/messages.php';
    require __DIR__ . '/dashboard/audit.php';
});

/**
 * Профиль пользователя / администратора
 */
Route::group(['middleware' => ['auth'], 'prefix' => 'profile', 'view' => 'profile'], function () {
    require __DIR__ . '/profile/home.php';
    require __DIR__ . '/profile/user.php';
    // require __DIR__ . '/profile/analytics.php';
    require __DIR__ . '/profile/tickets.php';
});
