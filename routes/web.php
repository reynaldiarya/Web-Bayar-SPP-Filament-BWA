<?php

use App\Filament\Pages\Payment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin/login');
});

Route::get('/admin/payment/{uuid}', Payment::class )->name('filament.page.payment');