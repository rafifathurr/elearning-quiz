<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZoomController extends Controller
{
    public function createMeeting(ZoomService $zoom)
    {
        $accessToken = $zoom->getAccessToken();

        // Ganti dengan email user Zoom di akunmu
        $userId = 'dipakeramerame3@gmail.com';

        $response = Http::withToken($accessToken)->post("https://api.zoom.us/v2/users/{$userId}/meetings", [
            'topic' => 'Kelas Belajar Laravel',
            'type' => 2, // scheduled meeting
            'start_time' => now()->addDay()->toIso8601String(),
            'duration' => 60,
            'timezone' => 'Asia/Jakarta',
            'agenda' => 'Materi hari ini: Auth & CRUD',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'waiting_room' => false,
            ],
        ]);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Meeting berhasil dibuat!',
                'join_url' => $response['join_url'],
                'start_url' => $response['start_url'],
            ]);
        }

        return response()->json(['error' => $response->json()], 500);
    }
}
