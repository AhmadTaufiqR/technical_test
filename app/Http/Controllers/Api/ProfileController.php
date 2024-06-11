<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $username = Cache::get('email');
        $token = Cache::get('token');

        $user = User::where('email', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'false',
                'message' => 'Profile gagal ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => 'true',
            'data' => new ProfileResource($user),
            'message' => 'Berhasil'
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            if ($user) {
                Cache::flush();
                Cache::add('email', $user->email);
                return response()->json([
                    'status' => true,
                    'data' => $user->id,
                    'message' => 'berhasil update profile',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak bisa dimasukkan'
            ], 404);
        }
    }
}
