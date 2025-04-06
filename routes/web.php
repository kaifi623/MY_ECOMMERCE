<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\Homepage;
use App\Livewire\MyOrdersDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', Homepage::class);

Route::get('/categories',CategoriesPage::class);

Route::get('/products',ProductsPage::class)->name('products');

Route::get('/cart',CartPage::class);

Route::get('/products/{slug}',ProductDetailPage::class);






Route::middleware('guest')->group(function(){
    Route::get('/login',LoginPage::class)->name('login');
    Route::get('/register',RegisterPage::class);
    Route::get('/forgot',ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}',ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function(){
    Route::get('/logout',function(){
    Auth::logout();
    return redirect('/');
    })->name('logout');
    Route::get('/success',SuccessPage::class)->name('success');
    Route::get('/cancel',CancelPage::class)->name('cancel');
    Route::get('/checkout',CheckoutPage::class);
    Route::get('/my-orders',MyOrdersPage::class);
    Route::get('/my-orders/{order_id}',MyOrdersDetailPage::class)->name('my-orders.show');
});



