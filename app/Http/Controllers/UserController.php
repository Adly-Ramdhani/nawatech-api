<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try{
            $data = User::all()->toArray();

            return ApiFormatter::sendResponse(200, 'success', $data);
        }catch(\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'username' => 'required',
                'email' => 'required|email',
            ]);

            if ($request->password) {
                $checkProses = User::where('id', $id)->update([
                    'username' => $request->username,
                    'email' => $request->email,
            
                    'password' => Hash::make($request->password)
                ]);
            } else {
                $checkProses = User::where('id', $id)->update([
                    'username' => $request->username,
                    'email' => $request->email,
                ]);
            }

            if ($checkProses) {
                $data = User::find($id);
                return ApiFormatter::sendResponse(200, 'success', $data);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal mengubah data!');
            }
        } catch (Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}
