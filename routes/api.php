<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

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
//GET /notes – Fetch all notes
//POST /notes – Create new note
//PUT /notes/{id} – Update note
//DELETE /notes/{id} – Delete note
Route::match(['get', 'post', 'put', 'delete'], '/notes/{id?}', [NoteController::class, 'notes']);
//Route::group(['middleware' => 'auth:api'], function () {
    //Route::match(['get', 'post', 'put', 'delete'], '/notes/{id?}', [NoteController::class, 'notes']);
    //Route::any('/notes', [NotesController::class, 'notes']);
    //Route::post('/notes', [NoteController::class, 'notes']);
    //Route::put('/notes', [NoteController::class, 'notes']);
    //Route::delete('/notes', [NoteController::class, 'notes']);
//});