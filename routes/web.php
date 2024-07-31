<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Geral Pages, soo for visitors.
|
*/

// GETS
Route::get('/', function () {
    return view('welcome');
});

// POSTS




/*
|--------------------------------------------------------------------------
| Game Rootes
|--------------------------------------------------------------------------
|
| Burada oyun için oda oluşturup odaya giriş yapma routingleri yönetilir.
|
*/
// GETS
Route::get("/create_room",                  [GameController::class, "createRoom"])->name("gameCreateRoom");
Route::get("/room/{room_id}",               [GameController::class, "getRoom"])->name("gameGetRoom");
Route::get("/ses",                          [GameController::class, "getSessionView"])->name("gameGetSess");
Route::get("/set_player_name",              [GameController::class, "setPlayerName"])->name("gameSetPlayerName");

// POSTS
Route::post("/create_room_process",         [GameController::class, "createRoomProcess"])->name("gameCreateRoomProcess");
Route::post("/set_player_name_process",     [GameController::class, "setPlayerNameProcess"])->name("gameSetPlayerNameProcess");
