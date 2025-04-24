<?php

namespace App\Http\Controllers;

use App\Models\TokenData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenDataController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = auth()->user();

        $existingToken = TokenData::where('token', $request->token)->first();
        if ($existingToken) {
            // Update user_id jika berbeda
            if ($existingToken->user_id !== $user->id) {
                $existingToken->user_id = $user->id;
                $existingToken->touch(); // update updated_at
                $existingToken->save();
            } else {
                $existingToken->touch(); // update updated_at
                $existingToken->save();
            }
        } else {
            // Token belum ada, simpan baru
            TokenData::create([
                'token' => $request->token,
                'user_id' => $user->id,
            ]);
        }
        return response()->json(['status' => 'success']);
    }
}
