<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\CheckAdminAccess;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page: Redirect automatically to app workspace if session context is active
Route::get('/', function () {
    return Auth::check() ? redirect()->route('tasks.index') : view('welcome');
});

// ---------------------------------------------------------
// STUDENT PORTAL (Requires Authentication & Verification)
// ---------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    
    // AI ENGINE ENDPOINTS
    Route::post('/dashboard/generate-analytics', [TaskController::class, 'generateDashboardAnalytics'])->name('dashboard.ai-analytics');
    Route::get('/tasks/{task}/ai-guidance', [TaskController::class, 'fetchAiGuidance'])->name('tasks.ai-guidance');

    // TASK MANAGEMENT WORKFLOWS
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::resource('tasks', TaskController::class)->except(['index']);
    Route::get('/tasks/{task}/view-material', [TaskController::class, 'viewMaterial'])->name('tasks.view-material');
    
    // STUDENT DASHBOARD
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
});

// ---------------------------------------------------------
// USER PROFILE SETTINGS
// ---------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bring in Breeze / Jetstream core authentication route blueprints (login, register)
require __DIR__.'/auth.php';


// ---------------------------------------------------------
// ISOLATED ADMIN PORTAL
// ---------------------------------------------------------
// ---------------------------------------------------------
// ISOLATED ADMIN PORTAL
// ---------------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 1. Admin Login Routes (Public facing for Admins)
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout'); 

    // 2. Admin Dashboard Protected Routes (Uses the new Middleware class)
    Route::middleware([CheckAdminAccess::class])->group(function () {
        
        // Remove the '/admin' prefix and 'admin.' name prefix here, 
        // because the parent group already applies them!
        
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
        
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
Route::get('/report/pdf', [AdminController::class, 'downloadPdf'])->name('report.pdf'); // NEW PDF ROUTE
    });
        
});


// ---------------------------------------------------------
// DIAGNOSTIC CORE ENDPOINTS (DEVELOPMENT TIER ONLY)
// ---------------------------------------------------------
Route::get('/test-library-ai', function () {
    $apiKey = env('GEMINI_API_KEY');
    
    if (!$apiKey) {
        return response()->json(['Status' => '❌ Missing GEMINI_API_KEY in your .env file']);
    }

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

    $payload = [
        'contents' => [
            [
                'parts' => [
                    ['text' => "Say the exact phrase: 'Gemini 2.5 Engine is Alive!'"]
                ]
            ]
        ]
    ];

    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->timeout(10)->post($url, $payload);

        if ($response->successful()) {
            return response()->json([
                'Status' => '✅ Connection Successful!',
                'Model_Used' => 'gemini-2.5-flash',
                'Response_From_Google' => $response->json()['candidates'][0]['content']['parts'][0]['text']
            ]);
        }

        return response()->json([
            'Status' => '❌ Google API Mapped, but Request Rejected',
            'HTTP_Status' => $response->status(),
            'Error_Details' => $response->json()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'Status' => '❌ Network Execution Error',
            'Message' => $e->getMessage()
        ]);
    }
});