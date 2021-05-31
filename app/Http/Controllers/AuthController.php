<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function signup(CreateUser $request)
    {
        $validateData = $request->validated();

        $user = new User([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => bcrypt($validateData['password']),
        ]);
        $user->save();

        return response(['message' => 'signup success'], 201);
    }

    public function signin(LoginUser $request)
    {
        $validateData = $request->validated();
        if (!Auth::attempt($validateData)) {
            return response(['token' => 'Authorization Failed']);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('token');
        $tokenResult->token->save();
        return response(['token' => $tokenResult->accessToken]);
    }

    public function signout(Request $request)
    {
        $request->user()->token()->revoke();

        return response(['message' => 'sign out success']);
    }


    public function user(Request $request)
    {
        return response(['data' => $request->user()]);
    }
}
