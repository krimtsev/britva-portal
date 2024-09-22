<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Upload\UploadController;
use App\Http\Controllers\UploadCategories\UploadCategoriesController;
use App\Http\Controllers\UploadFiles\UploadFilesController;


Route::group(['prefix' => 'upload'], function () {
    Route::get('/', [UploadController::class, 'index'])->name('d.upload.index');
    Route::get('/create', [UploadController::class, 'create'])->name('d.upload.create');
    Route::post('/', [UploadController::class, 'store'])->name('d.upload.store');
    Route::get('/{upload}/edit', [UploadController::class, 'edit'])->name('d.upload.edit');
    Route::patch('/{upload}', [UploadController::class, 'update'])->name('d.upload.update');
    Route::get('/{upload}', function ($upload) {
        return redirect()->route('d.upload.edit', $upload);
    });
    // Route::delete('/{upload}', [UploadController::class, 'destroy'])->name('d.upload.delete');
});

Route::group(['prefix' => 'upload-categories'], function () {
    Route::get('/', [UploadCategoriesController::class, 'index'])->name('d.upload-categories.index');
    Route::get('/create', [UploadCategoriesController::class, 'create'])->name('d.upload-categories.create');
    Route::post('/', [UploadCategoriesController::class, 'store'])->name('d.upload-categories.store');
    Route::get('/{category}/edit', [UploadCategoriesController::class, 'edit'])->name('d.upload-categories.edit');
    Route::patch('/{category}', [UploadCategoriesController::class, 'update'])->name('d.upload-categories.update');
    // Route::delete('/{category}', [UploadCategoriesController::class, 'destroy'])->name('d.upload-categories.delete');
});

Route::group(['prefix' => 'upload-files'], function () {
    Route::get('/', [UploadFilesController::class, 'index'])->name('d.upload-files.index');
    Route::get('/{file}/edit', [UploadFilesController::class, 'edit'])->name('d.upload-files.edit');
    Route::patch('/{file}', [UploadFilesController::class, 'update'])->name('d.upload-files.update');
    Route::get('/{file}/delete', [UploadFilesController::class, 'destroy'])->name('d.upload-files.delete');
});
