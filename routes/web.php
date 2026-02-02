<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

// Import Controllers
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// ផ្នែកទី ១: Route សាធារណៈ (Public / POS)
// ============================================

Route::get('/', function () {
    $products = Product::all();
    return view('welcome', compact('products'));
})->name('home');

// Login Routes
Route::get('/login', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' ? redirect()->route('dashboard') : redirect('/');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('home');
    }

    return back()->withErrors([
        'email' => 'អ៊ីមែល ឬ លេខសម្ងាត់មិនត្រឹមត្រូវ!',
    ]);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// ============================================
// ផ្នែកទី ២: Route សម្រាប់បុគ្គលិកទាំងអស់ (Auth Required)
// ============================================
Route::middleware(['auth'])->group(function () {
    
    // POS System (Save & Print)
    Route::post('/pos/store', [PosController::class, 'store']);
    Route::get('/pos/print/{id}', [PosController::class, 'printReceipt']);

    // ប្រវត្តិការលក់ (មើលបានទាំងអស់គ្នា)
    Route::get('/sales-history', [PosController::class, 'history'])->name('sales.history');
    
    // 🔥 បន្ថែមថ្មី៖ Route សម្រាប់ Export Excel នៅទំព័រ Sales History
    Route::get('/sales/export', [PosController::class, 'export'])->name('sales.export');

    // Profile ផ្ទាល់ខ្លួន
    Route::get('/admin/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/admin/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/admin/profile/update', [ProfileController::class, 'update'])->name('profile.update');

});


// ============================================
// ផ្នែកទី ៣: Route សម្រាប់តែ Admin (Middleware 'admin') 🛡️
// ============================================
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard & Reports
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/export-csv', [DashboardController::class, 'exportCsv'])->name('admin.export'); 

    // 🔥 Barcode Route (ដាក់នៅពីលើ Resource ដើម្បីកុំឱ្យជាន់គ្នា)
    Route::get('/admin/products/{id}/print-barcode', [ProductController::class, 'printBarcode'])
        ->name('products.printBarcode');

    // CRUD Operations
    Route::resource('/admin/products', ProductController::class);
    Route::resource('/admin/users', UserController::class);
    Route::resource('/admin/expenses', ExpenseController::class);

    // Settings & Tools
    Route::get('/admin/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/admin/settings/update', [ProfileController::class, 'updateSettings'])->name('settings.update');
    
    // Route សម្រាប់លុបការលក់
    Route::delete('/sales/delete/{id}', [PosController::class, 'destroy'])->name('sales.destroy');

    // Reset Database
    Route::get('/admin/reset-sales', function () {
        Schema::disableForeignKeyConstraints();
        \App\Models\OrderItem::truncate();
        \App\Models\Order::truncate();
        \App\Models\Expense::truncate();
        Schema::enableForeignKeyConstraints();
        return redirect('/admin/dashboard')->with('success', 'ទិន្នន័យការលក់ត្រូវបានសម្អាតទាំងស្រុង!');
    });

});