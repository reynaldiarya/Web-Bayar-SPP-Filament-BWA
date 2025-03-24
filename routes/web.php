<?php

use App\Filament\Pages\Payment;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin/login');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin/payment/{uuid}', Payment::class)->name('filament.page.payment');
    Route::get('/file/{fileName}', [FileController::class, 'getFile'])->where('fileName', '.*')->name('file.get');
});
