<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameRoom;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GameController extends Controller
{










    /* öndemli kod */
    /* öndemli kod */
    /* öndemli kod */
    /* öndemli kod */
    /* öndemli kod */
    public function getRoom($room_id)
    {
        try {
            // UUID formatını kontrol et
            if (!Str::isUuid($room_id)) {
                // view('get_room', $data)
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid room ID format'
                ], 400);
            }

            // Room modelini kullanarak oda bilgilerini al
            $room = GameRoom::findOrFail($room_id);

            return response()->json([
                'success' => true,
                'data' => $room
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found'
            ], 404);
        } catch (\Exception $e) {
            // Diğer tüm hatalar için genel bir catch bloğu
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }















}


