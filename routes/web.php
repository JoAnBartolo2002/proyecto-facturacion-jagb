<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InvoiceController;
use App\Models\Invoice; 

Route::redirect('/home', '/dashboard');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_invoiced' => (float) $user->invoices()->sum('total_amount'),
                
                'count'          => (int) $user->invoices()->count(),
            ]
        ]);
    })->name('dashboard');

    Route::resource('invoices', InvoiceController::class);
});

require __DIR__.'/settings.php';
