<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function Login(Request $request)
    {

        //validation request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.email' => 'Masukkan email yang tepat',
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 404);
        }

        $user = User::where('email', $request->email)->first();

        //Check Email same
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        //check password same
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password yang anda masukkan salah'
            ], 404);
        }

        // check cache if cache not null, cache delete it, but cache is null, cache added
        $caches = Cache::get('email');
        if ($caches) {
            Cache::flush();
        } else {
            Cache::add('email', $request->email);
        }

        return response()->json([
            'status' => true,
            'data' => $user->createToken('AuthToken')->plainTextToken,
            'message' => 'login berhasil'
        ]);
    }
}
