<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KamarKosanController;
use App\Http\Controllers\Admin\KosanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\SosialMediaController;
use App\Http\Controllers\TamuBookingController;
use App\Http\Controllers\TestimoniController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public / Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kosan', [HomeController::class, 'kosanIndex'])->name('kosan.index');
Route::get('/kosan/detail', fn() => redirect()->route('kosan.index'));
Route::get('/kosan/detail/{slug}', [HomeController::class, 'kosanDetail'])->name('kosan.detail');
Route::get('/kamar/detail/{product_kamar_kosan?}', [HomeController::class, 'kamarDetail'])->name('kamar.detail');
Route::get('/news', [HomeController::class, 'newsIndex'])->name('news.index');
Route::get('/news/detail/{slug}', [HomeController::class, 'newsDetail'])->name('news.detail');

Route::get('/kamar/booking/{product_kamar_kosan}', [HomeController::class, 'formBooking'])->name('form.booking.kamar');

Route::post('/booking/kamar/{product_kamar_kosan}', [TamuBookingController::class, 'booking'])->middleware('throttle:10,1')->name('tamu.booking');
Route::get('/booking/success/{token}', [TamuBookingController::class, 'bookingSuccess'])->name('booking.success');
Route::get('/booking/download-invoice/{token}', [TamuBookingController::class, 'downloadInvoice'])->name('booking.download.invoice');

Route::get('/check-date/kamar/{id}', [HomeController::class, 'checkDateKamar'])->name('check.date.kamar');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Requires Auth)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin & Cabang Management (Super Admin only)
    Route::prefix('/users')->middleware(['super_admin'])->controller(AdminManagementController::class)->name('users.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::put('/update/{user}', 'update')->name('update');
        Route::delete('/destroy/{user}', 'destroy')->name('destroy');
    });

    // Kosan Management
    Route::prefix('/product-kosan')->controller(KosanController::class)->name('product.kosan')->group(function(){
        Route::get('/index', 'index')->name('.index');
        Route::get('/search-ajax', 'searchKosan')->name('.search.ajax');
        Route::post('/insert', 'insert')->name('.insert')->middleware('super_admin');
        Route::put('/update/{product_kosan}', 'update')->name('.update');
        Route::delete('/delete/{product_kosan}', 'delete')->name('.delete')->middleware('super_admin');

        Route::get('/image/{product_kosan}', 'indexImage')->name('.image.index');
        Route::post('/image/{product_kosan}/insert', 'insertImage')->name('.image.insert');
        Route::delete('/image/delete/{product_image_kosan}', 'deleteImage')->name('.image.delete');
    });

    // Kamar Kosan Management & Sub-resources
    Route::prefix('/product-kosan/{product_kosan}/kamar')->controller(KamarKosanController::class)->name('product.kosan.kamar')->group(function(){
        Route::get('/index', 'indexKamar')->name('.index');
        Route::get('/search-ajax', 'searchKamar')->name('.search.ajax');
        Route::post('/insert', 'insertKamar')->name('.insert');
        Route::put('/update/{product_kamar_kosan}', 'updateKamar')->name('.update');
        Route::delete('/delete/{product_kamar_kosan}', 'deleteKamar')->name('.delete');

        Route::prefix('{product_kamar_kosan}/fasilitas')->name('.fasilitas')->group(function(){
            Route::post('/insert', 'insertFasilitas')->name('.insert');
            Route::put('/update/{fasilitas_kamar}', 'updateFasilitas')->name('.update');
            Route::delete('/delete/{fasilitas_kamar}', 'deleteFasilitas')->name('.delete');
        });

        Route::prefix('{product_kamar_kosan}/image')->name('.image')->group(function(){
            Route::post('/insert', 'insertKamarImage')->name('.insert');
            Route::delete('/delete/{product_kamar_image_kosan}', 'deleteKamarImage')->name('.delete');
        });

        Route::prefix('{product_kamar_kosan}/price-kamar')->name('.price.kamar')->group(function(){
            Route::post('/insert', 'insertPriceKamar')->name('.insert');
            Route::put('/update/{price_kamar}', 'updatePriceKamar')->name('.update');
            Route::delete('/delete/{price_kamar}', 'deletePriceKamar')->name('.delete');
        });

        Route::prefix('{product_kamar_kosan}/tamu')->name('.tamu')->group(function(){
            Route::put('/renew/{tamu}', 'renewTamu')->name('.renew');
            Route::delete('/delete/{tamu}', 'deleteTamu')->name('.delete');
        });
    });

    // Booking Approval Management
    Route::prefix('/booking')->controller(BookingAdminController::class)->name('booking.')->group(function(){
        Route::get('/index', 'indexBooking')->name('index');
        Route::put('/approve/{tamu}', 'approveBooking')->name('approve');
        Route::put('/reject/{tamu}', 'rejectBooking')->name('reject');
        Route::get('/proof/{tamu}', 'viewProof')->name('proof');
    });

    // Social Media
    Route::prefix('/sosial-media')->controller(SosialMediaController::class)->name('sosial.media')->group(function(){
        Route::get('/index', 'index')->name('.index');
        Route::get('/search-ajax', 'searchAjax')->name('.search.ajax');
        Route::post('/insert', 'insert')->name('.insert');
        Route::put('/update/{sosial_media}', 'update')->name('.update');
        Route::delete('/delete/{sosial_media}', 'delete')->name('.delete');
    });

    // Artikel
    Route::prefix('/artikel')->controller(ArtikelController::class)->name('artikel')->group(function(){
        Route::get('/index', 'index')->name('.index');
        Route::get('/search-ajax', 'searchAjax')->name('.search.ajax');
        Route::post('/insert', 'insert')->name('.insert');
        Route::put('/update/{artikel}', 'update')->name('.update');
        Route::delete('/delete/{artikel}', 'delete')->name('.delete');
    });

    // Testimoni
    Route::prefix('/testimoni')->controller(TestimoniController::class)->name('testimoni')->group(function(){
        Route::get('/index', 'index')->name('.index');
        Route::get('/search-ajax', 'searchAjax')->name('.search.ajax');
        Route::post('/insert', 'insert')->name('.insert');
        Route::put('/update/{testimoni}', 'update')->name('.update');
        Route::delete('/delete/{testimoni}', 'delete')->name('.delete');
    });

    // Profile & Password Management
    Route::prefix('/profile')->controller(ProfileController::class)->name('profile.')->group(function(){
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'updateProfile')->name('update');
        Route::put('/password', 'updatePassword')->name('password');
    });

});
