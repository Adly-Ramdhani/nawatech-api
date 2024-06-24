<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Helpers\ApiFormatter;
use Illuminate\Http\Request; 
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request) 
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Coba mencari pengguna berdasarkan email
    $user = User::where('email', $request->email)->first();

    // Jika pengguna tidak ditemukan, kembalikan respon kesalahan
    if (!$user) {
        return response()->json([
            'message' => 'User not found',
        ], 404);
    }


    // Jika kata sandi cocok, buat token dan kembalikan
    return response()->json([
        'token' => $user->createToken('user login')->plainTextToken,
    ], 200);
}


   public function logout(Request $request)
   {
    // Ambil user dari request
    $user = $request->user();

    // Pastikan user terautentikasi
    if ($user) {
        // Hapus access token saat ini
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    } else {
        // Jika user tidak terautentikasi
        return response()->json(['error' => 'No user authenticated'], 401);
    }
  }

    public function me(Request $request)
    {
        return ApiFormatter::sendResponse(200, 'success', auth()->user());
    }

    public function register (Request $request)
    {
        try{

            $username= $request->input('username');
            $email = $request->input('email');
            $password = Hash::make($request->input('passowrd'));
    
    
            $register = User::create([
                'username' => $username,
                'email' => $email, 
                'password' => $password,
 
            ]);
            return ApiFormatter::sendResponse(200, true, 'Successfully Create A User Data', $register);
            }catch(\Exception $e){
                return ApiFormatter::sendResponse(400, false, $e->getMessage());
            }
    }

}  

    
    

