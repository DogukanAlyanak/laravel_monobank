<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameRoom;
use App\Models\GamePlayer;
use App\Models\GameBankAccount;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class GameController extends Controller {


    protected $game_start_balance;

    public function __construct() {
        $this->game_start_balance = env('GAME_START_BALANCE', 0);
    }





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
        $gameRoom->room_name = trim($request->roomName);
        $gameRoom->save();

        // Return a JSON response with the URL to redirect to
        return response()->json(['data' => $gameRoom, 'redirect' => route('gameGetRoom', ['room_id' => $gameRoom->id])]);
    }



    /* --- ODA YA GİRİŞ YAP ------------------------- */
    public function getRoom($room_id) {
        try {
            // UUID formatını kontrol et
            if (!Str::isUuid($room_id)) {
                return view('game.get_room',  ['redirect' => route('gameCreateRoom')]);
            }


            // Tüm session verisini almak (tüm verileri array olarak alır)
            $allSessionData = Session::all();

            // Session data controller
            $sessionToken = $allSessionData["_token"];
            if ($sessionToken) {
                $gamePlayer = GamePlayer::where('session_token', $sessionToken)->first();

                if ($gamePlayer == null) {
                    $gamePlayer = new GamePlayer;
                    $gamePlayer->session_token = $sessionToken;
                    $gamePlayer->save();
                }
            }

            // Room modelini kullanarak oda bilgilerini al (hata alırsan id li oda bulunamyan hataya gönder (catch))
            $room = GameRoom::findOrFail($room_id);


            // room oluşturulmuş veya bulunmuşsa hemen banka hesabını oluştur.
            $gameBankAccount
                = GameBankAccount::where('room_id', $room_id)
                                 ->where('player_session_token', $sessionToken)
                                 ->first();

            if ($gameBankAccount == null) {
                $gameBankAccount = new GameBankAccount;
                $gameBankAccount->room_id = $room_id;
                $gameBankAccount->player_session_token = $sessionToken;
                $gameBankAccount->balance = $this->game_start_balance;
                $gameBankAccount->save();
            }


            // Eğer oyuncu kendi adını girmemişse oyuncu adını girmeye yönlendir. (user name = player name)
            if (empty($gamePlayer->player_name)) {
                return view('game.get_room',  ['redirect' => route('gameSetPlayerName',  ['room_id' => $room_id])]);
            }

            // Bir sıkıntı yoksa odaya gönder
            return view('game.get_room',  ['room' => $room, 'thisPlayer' => $gamePlayer]);



        } catch (ModelNotFoundException $e) {
            // Bu id ye ait oda bulunamadığı zaman
            return view('game.get_room',  ['redirect' => route('gameCreateRoom')]);



        } catch (\Exception $e) {
            return response()->json($e);


            // Diğer tüm hatalar için genel bir catch bloğu
            return view('game.get_room',  ['redirect' => route('gameCreateRoom')]);
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


    /* --- SET OWN PLAYER NAME ---------------------------- */
    public function setPlayerName(Request $request) {

        $request->validate([
            'room_id' => 'required|string|max:700',
        ]);

        $roomID = $request->room_id;

        // Tüm session verisini almak (tüm verileri array olarak alır)
        $allSessionData = Session::all();

        // Session data controller
        if ($allSessionData["_token"]) {
            $sessionToken = $allSessionData["_token"];
            $gamePlayer = GamePlayer::where('session_token', $allSessionData["_token"])->first();

            if ($gamePlayer == null) {
                $gamePlayer = new GamePlayer;
                $gamePlayer->session_token = $sessionToken;
                $gamePlayer->save();
            }
        }

        return view('game.set_player_name', [
            "player" => $gamePlayer,
            "room_id" => $roomID
        ]);
    }





    /* --- SET OWN PLAYER NAME --- PROCESS ---------------------------- */
    public function setPlayerNameProcess(Request $request) {

        // Validation
        $request->validate([
            'playerName' => 'required|string|max:255',
            'roomID' => 'required|string|max:700',
        ]);

        // Requests
        $roomID = $request->roomID;
        $playerName = $request->playerName;

        // Tüm sessionlar
        $allSessionData = Session::all();

        // Session data controllers
        if ($allSessionData["_token"]) {

            // player process
            $sessionToken = $allSessionData["_token"];
            $gamePlayer = GamePlayer::where('session_token', $allSessionData["_token"])->first();

            if ($gamePlayer == null) {
                $gamePlayer = new GamePlayer;
                $gamePlayer->session_token = $sessionToken;
            }

            $gamePlayer->player_name = trim($playerName);
            $gamePlayer->save();


            // room process
            $gameRoom = GameRoom::where('id', $roomID)->first();


            // successfull response
            if ($gameRoom != null) {
                return response()->json([
                    'redirect' => route('gameGetRoom',  ['room_id' => $gameRoom->id])
                ], 200);
            } else {
                return response()->json([
                    'redirect' => route('createRoom')
                ], 200);
            }

            // successfull response
            /* return response()->json([
                'player' => $gamePlayer,
                'room' => $gameRoom
            ], 200); */
        }


        // if have some errors.
        return response()->json('Bad Request', 400);
    }





    /* --- GET PLAYER ACCOUNTS FROM ROOM --- PROCESS ---------------------------- */
    public function getPlayerAccountsFromRoom(Request $request) {

        // Validation
        $request->validate([
            'room_id' => 'required|string|max:700',
        ]);

        // Requests
        $roomID = $request->room_id;

        try {

            // --- This Player Account Controller -------

            // Tüm sessionlar
            $allSessionData = Session::all();

            // Session data controllers
            if ($allSessionData["_token"]) {
                $sessionToken = $allSessionData["_token"];

                $thisPlayerAccount
                    = GameBankAccount::where('room_id', $roomID)
                                     ->where('player_session_token', $sessionToken)
                                     ->first();

                if ($thisPlayerAccount == null) {
                    $thisPlayerAccount = new GameBankAccount;
                    $thisPlayerAccount->room_id = $roomID;
                    $thisPlayerAccount->player_session_token = $sessionToken;
                    $thisPlayerAccount->balance = $this->game_start_balance;
                    $thisPlayerAccount->save();
                }
            }

            // Odaya göre oyuncu hesaplarını getir.
            // $playerAccounts = GameBankAccount::where('room_id', $roomID)->select('id', 'player_session_token', 'balance')->get(); // not need this codes
            $playerAccounts = DB::table('game_bank_accounts')
                    ->join('game_players', 'game_bank_accounts.player_session_token', '=', 'game_players.session_token')
                    ->select('game_bank_accounts.id',
                             'game_bank_accounts.player_session_token',
                             'game_bank_accounts.balance',
                             'game_bank_accounts.is_banker',
                             'game_players.player_name')
                    ->where('room_id', $roomID)
                    ->get();


            // oyuncu hesap verileri gönder
            return response()->json(['success' => true, 'data' => $playerAccounts]);
        } catch (\Exception $e) {

            // herhangi bir hata durumunda hata gönder
            return response()->json(['success' => false, 'error' => $e], 501);
        }
    }





    /* --- SET PLAYER BANKER --- PROCESS ---------------------------- */
    public function setPlayerBanker(Request $request) {

        // Validation
        $request->validate([
            'player_token' => 'required|string|max:700',
            'room_id' => 'required|string|max:700',
        ]);

        // Requests
        $playerToken    = $request->player_token;
        $roomID         = $request->room_id;

        try {

            // Odadaki Tüm Oyuncuları Listele
            $records = GameBankAccount::where('room_id', $roomID)
                                      ->get();

            // Tüm Oyuncuların bankacı görevini elinden al
            foreach ($records as $record) {
                $record->is_banker = 0;
                $record->save();
            }


            // Seçili oyuncu
            $selectedPlayerAccount
                    = GameBankAccount::where('room_id', $roomID)
                                     ->where('player_session_token', $playerToken)
                                     ->first();

            // Seçili oyuncuya Bankacı Görevi Ata
            $selectedPlayerAccount->is_banker = 1;
            $selectedPlayerAccount->save();

        } catch (\Exception $e) {

            // herhangi bir hata durumunda hata gönder
            return response()->json(['success' => false, 'error' => $e], 501);
        }

    }
}
