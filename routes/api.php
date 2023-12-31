<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth.api')->group(function () {
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::post('/logout', [AuthController::class, 'logout']);
// });


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth.api:superadmin'])->group(function () {
    Route::post('/refresh', [AuthController::class, 'refresh']);
   // Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware(['auth.api:admin'])->group(function () {
   // Route::post('/refresh', [AuthController::class, 'refresh']);
   // Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
