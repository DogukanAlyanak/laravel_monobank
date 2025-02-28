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
Route::get("/create_room",                          [GameController::class, "createRoom"])->name("gameCreateRoom");
Route::get("/set_player_name",                      [GameController::class, "setPlayerName"])->name("gameSetPlayerName");
Route::get("/room/{room_id}",                       [GameController::class, "getRoom"])->name("gameGetRoom");
Route::get("/room/{room_id}/player/me",             [GameController::class, "getThisPlayerScreen"])->name("gameGetThisPlayerScreen");

// TESTS
// Route::get("/ses",                                  [GameController::class, "getSessionView"])->name("gameGetSess");

// POSTS
Route::post("/create_room_process",                 [GameController::class, "createRoomProcess"])->name("gameCreateRoomProcess");
Route::post("/set_player_name_process",             [GameController::class, "setPlayerNameProcess"])->name("gameSetPlayerNameProcess");
Route::post("/get_player_accounts_from_room",       [GameController::class, "getPlayerAccountsFromRoom"])->name("gameGetPlayerAccountsFromRoom");
Route::post("/set_player_banker_to_this_room",      [GameController::class, "setPlayerBanker"])->name("gameSetPlayerBanker");
