<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;  //è 'Illuminate\...'!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){   //return token if credentials in input are correct
        //validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        //find the user with target email
        $user = \App\Models\User::where('email',$request->email)->first();
        //check user's credentials
        if(!$user){
            throw ValidationException::withMessages([
                'email'=> ['The provided credentials are incorrect.']
            ]);
        }
        if(!Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email'=> ['The provided credentials are incorrect.']
            ]);
        }
        //create API token x the user + return
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token
        ]);
    }
    public function logout(REquest $request){   //delete all tokens of the user(logged in)
        //delete all tokens of the user
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out succesfully'
        ]);
    }
}
