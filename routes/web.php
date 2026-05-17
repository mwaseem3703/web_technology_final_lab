<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Gemini\Laravel\Facades\Gemini;

// Landing Page: Redirect if logged in
Route::get('/', function () {
    return Auth::check() ? redirect()->route('tasks.index') : view('welcome');
});

// Protected Area
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/dashboard/generate-analytics', [TaskController::class, 'generateDashboardAnalytics'])->name('dashboard.ai-analytics');
// Route to force inline browser viewing instead of automatic downloads
    Route::get('/tasks/{task}/view-material', [TaskController::class, 'viewMaterial'])->name('tasks.view-material');
    // HOME: Task Management
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::resource('tasks', TaskController::class)->except(['index']);
    Route::get('/tasks/{task}/ai-guidance', [TaskController::class, 'fetchAiGuidance'])->name('tasks.ai-guidance');
    // DASHBOARD: Analytics
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





require __DIR__.'/auth.php';




use Illuminate\Support\Facades\Http;

Route::get('/test-library-ai', function () {
    $apiKey = env('GEMINI_API_KEY');
    
    if (!$apiKey) {
        return response()->json(['Status' => '❌ Missing GEMINI_API_KEY in your .env file']);
    }

    // Target the accurate v1beta endpoint with your explicit gemini-2.5-flash model
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