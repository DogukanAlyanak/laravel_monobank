<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameRoom;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    /* --- ODA OLUŞTUR ------------------------- */
    public function createRoom() {
        return view('game.create_room');
    }



    /* --- ODA OLUŞTUR - PROCESS ------------------------- */
    public function createRoomProcess(Request $request) {

        // Validation
        $request->validate([
            'roomName' => 'required|string|max:255',
        ]);

        // Model ile veritabanına kayıt
        $gameRoom = new GameRoom;
        $gameRoom->room_name = $request->roomName;
        $gameRoom->save();

        // Return a JSON response with the URL to redirect to
        return response()->json(['data' => $gameRoom, 'redirect' => route('gameGetRoom', ['room_id' => $gameRoom->id])]);
    }



    /* --- ODA YA GİRİŞ YAP ------------------------- */
    public function getRoom($room_id) {
        try {
            // UUID formatını kontrol et
            if (!Str::isUuid($room_id)) {
                return view('game.get_room',  ['data' => "1", 'redirect' => route('gameCreateRoom')]);
            }



            // Room modelini kullanarak oda bilgilerini al
            $room = GameRoom::findOrFail($room_id);
            return view('game.get_room',  ['data' => $room]);



        } catch (ModelNotFoundException $e) {
            // Bu id ye ait oda bulunamadığı zaman
            return view('game.get_room',  ['data' => "2", 'redirect' => route('gameCreateRoom')]);



        } catch (\Exception $e) {
            // Diğer tüm hatalar için genel bir catch bloğu
            return view('game.get_room',  ['data' => "3", 'redirect' => route('gameCreateRoom')]);
        }
    }


    /* --- GET SESSION ---------------------------- */
    public function getSessionView() {
        // Session verisini almak
        $value = Session::get('key');

        // Session verisi mevcut değilse varsayılan bir değer belirlemek
        $value = Session::get('key', 'default');

        // Session verisini ayarlamak
        Session::put('key', 'value');

        // Session verisini silmek
        Session::forget('key');

        // Tüm session verisini almak (tüm verileri array olarak alır)
        $allSessionData = Session::all();

        return response()->json([
            'session' => $allSessionData
        ], 200, [], JSON_PRETTY_PRINT);
    }


    /* --- GET SESSION ---------------------------- */
    public function defineNickname() {

    }
}
