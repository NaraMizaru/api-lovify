<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CateringController;
use App\Http\Controllers\API\DecorationController;
use App\Http\Controllers\API\MuaController;
use App\Http\Controllers\API\PacketController;
use App\Http\Controllers\API\PhotographerController;
use App\Http\Controllers\API\VenueController;
use App\Http\Controllers\API\WeddingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Route::get('/admin/home')

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function() {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/venues', [VenueController::class, 'getVenue']);
    Route::post('/venue/create', [VenueController::class, 'createVenue']);
    Route::post('/venue/{id}/update', [VenueController::class, 'updateVenue']);
    Route::delete('/venue/{id}/delete', [VenueController::class, 'deleteVenue']);

    Route::get('/caterings', [CateringController::class, 'getCatering']);
    Route::post('/catering/create', [CateringController::class, 'createCatering']);
    Route::post('/catering/{id}/update', [CateringController::class, 'updateCatering']);
    Route::delete('/catering/{id}/delete', [CateringController::class, 'deleteCatering']);

    Route::get('/muas', [MuaController::class, 'getMua']);
    Route::post('/mua/create', [MuaController::class, 'createMua']);
    Route::post('/mua/{id}/update', [MuaController::class, 'updateMua']);
    Route::delete('/mua/{id}/delete', [MuaController::class, 'deleteMua']);

    Route::get('/decorations', [DecorationController::class, 'getDecoration']);
    Route::post('/decoration/create', [DecorationController::class, 'createDecoration']);
    Route::post('/decoration/{id}/update', [DecorationController::class, 'updateDecoration']);
    Route::delete('/decoration/{id}/delete', [DecorationController::class, 'deleteDecoration']);
    
    Route::get('/photographers', [PhotographerController::class, 'getPhotographer']);
    Route::post('/photographer/create', [PhotographerController::class, 'createPhotographer']);
    Route::post('/photographer/{id}/update', [PhotographerController::class, 'updatePhotographer']);
    Route::delete('/photographer/{id}/delete', [PhotographerController::class, 'deletePhotographer']);

    Route::get('/packets', [PacketController::class, 'getAllPacket']);
    Route::post('/packet/create', [PacketController::class, 'createPacket']);
    Route::get('/packet/{id}/detail', [PacketController::class, 'getDetailPacket']);
    Route::put('/packet/{id}/update', [PacketController::class, 'updatePacket']);
    Route::delete('/packet/{id}/delete', [PacketController::class, 'deletePacket']);

    Route::get('/weddings', [WeddingController::class, 'getAllWedding']);
    Route::get('/weddings/user', [WeddingController::class, 'getUserWedding']);
    Route::post('/wedding/packet/create', [WeddingController::class, 'createWeddingPacket']);
    Route::put('/wedding/packet/{id}/update', [WeddingController::class, 'updateWeddingPacket']);

    Route::post('/wedding/planning/create', [WeddingController::class, 'createWeddingPlanning']);
    Route::put('/wedding/planning/{id}/update', [WeddingController::class, 'updateWeddingPlanning']);

    Route::delete('/wedding/{id}/delete', [WeddingController::class, 'deleteWedding']);
});
