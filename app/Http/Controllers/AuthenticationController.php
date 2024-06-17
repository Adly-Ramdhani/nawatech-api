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
       $user = User::where('email', $request->email)->first();
       if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
     }
     return $user->createToken('user login')->plainTextToken;
    }

    public function logout(Request $request)
     {
        $request->user()->currentAccessToken()->delete();

    
         return response()->json(['message' => 'Logged out successfully']);
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
