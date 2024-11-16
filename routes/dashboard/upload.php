<?php

use App\Http\Controllers\Upload\UploadController;
use App\Http\Controllers\Upload\UploadFilesController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'upload'], function () {
    // Альтернативная вид списка
    Route::get('/table', [UploadController::class, 'table'])
        ->name('d.upload.table');

    Route::get('/', [UploadController::class, 'index'])->name('d.upload.index');
    Route::get('/create', [UploadController::class, 'create'])->name('d.upload.create');
    Route::post('/', [UploadController::class, 'store'])->name('d.upload.store');
    Route::get('/{upload}/edit', [UploadController::class, 'edit'])->name('d.upload.edit');
    Route::patch('/{upload}', [UploadController::class, 'update'])->name('d.upload.update');
    Route::get('/{upload}', function ($upload) {
        return redirect()->route('d.upload.edit', $upload);
    });
    Route::delete('/{upload}', [UploadController::class, 'destroy'])->name('d.upload.delete');
});

Route::group(['prefix' => 'upload-files'], function () {
    Route::get('/', [UploadFilesController::class, 'index'])->name('d.upload-files.index');
    Route::get('/{file}/edit', [UploadFilesController::class, 'edit'])->name('d.upload-files.edit');
    Route::patch('/{file}', [UploadFilesController::class, 'update'])->name('d.upload-files.update');
    Route::get('/{file}/delete', [UploadFilesController::class, 'destroy'])->name('d.upload-files.delete');
});
