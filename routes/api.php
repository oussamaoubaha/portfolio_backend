<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\GeminiController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

// Public Routes
Route::get('/ping', function() { return response()->json(['status' => 'ok']); });
Route::get('/test-connection', function() { return response()->json(['status' => 'connected']); });
Route::post('/chat', [GeminiController::class, 'chat']);
Route::get('/profile', [ProfileController::class, 'index']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/projects', [\App\Http\Controllers\Api\ProjectController::class, 'index']);
Route::get('/experiences', [ExperienceController::class, 'index']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']); // Public submission

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Admin Routes
    Route::post('/profile', [ProfileController::class, 'update']);
    
    Route::apiResource('skills', SkillController::class)->except(['index', 'show']);
    Route::apiResource('projects', \App\Http\Controllers\Api\ProjectController::class)->except(['index', 'show']);
    Route::apiResource('experiences', ExperienceController::class)->except(['index', 'show']);
    Route::put('/formations/{experience}', [ExperienceController::class, 'update']);
    Route::apiResource('reviews', ReviewController::class)->except(['index', 'store', 'show']);
    Route::patch('/reviews/{review}/publish', [ReviewController::class, 'publish']);
    Route::patch('/avis/{review}/publish', [ReviewController::class, 'publish']);
    
    // Admin specific review route
    Route::get('/admin/reviews', [ReviewController::class, 'indexAdmin']);

    // AI Conversations Admin Routes
    Route::get('/admin/ai-logs', [GeminiController::class, 'indexAdmin']);
    Route::delete('/admin/ai-logs/{log}', [GeminiController::class, 'destroyAdmin']);
    
    // Knowledge Base Admin Routes
    Route::apiResource('assistant-knowledge', \App\Http\Controllers\Api\AssistantKnowledgeController::class);
    
    // Assistant Settings Routes
    Route::get('/assistant-settings', [\App\Http\Controllers\Api\AssistantSettingController::class, 'index']);
    Route::put('/assistant-settings/{key}', [\App\Http\Controllers\Api\AssistantSettingController::class, 'update']);

    // AI Sessions & History Routes
    Route::get('/ai-sessions', [\App\Http\Controllers\Api\AISessionController::class, 'index']);
    Route::get('/ai-sessions/{id}', [\App\Http\Controllers\Api\AISessionController::class, 'show']);
    Route::delete('/ai-sessions/{id}', [\App\Http\Controllers\Api\AISessionController::class, 'destroy']);
    
    // Compatibility route for existing AdminAILogs
    Route::get('/admin/ai-logs', [\App\Http\Controllers\Api\GeminiController::class, 'indexAdmin']);
});

// DEBUG HELPER (Remove in production)
Route::get('/debug-auth', function () {
    $email = 'admin@oussama.com';
    $password = '97999799';
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) return response()->json(['error' => 'User not found'], 404);
    
    $check = \Illuminate\Support\Facades\Hash::check($password, $user->password);
    
    return response()->json([
        'user_id' => $user->id,
        'email' => $user->email,
        'password_is_hashed' => strlen($user->password) > 20,
        'hash_check_success' => $check,
        'hash_sample' => substr($user->password, 0, 10) . '...',
        'rehash_needed' => \Illuminate\Support\Facades\Hash::needsRehash($user->password),
    ]);
});
