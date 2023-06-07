<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SingupRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(SingupRequest $request)
    {
        // try {
        $data = $request->validated();
        // $exist_user = User::where('email',$data['name'])->first();
        // if ($exist_user) {
        //     return response()->json([
        //         'status_code' => 422,
        //         'message' => 'Email already exists',

        //     ]);
        // }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        // event(new Registered($user));
        $user->sendVerificationEmail();

        $token = $user->createToken($user->email)->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
        // } catch (Exception $error) {
        //     return response()->json([
        //         'status_code' => 500,
        //         'message' => 'Error in signup',
        //         'error' => $error,
        //     ]);
        // }
    }

    public function login(LoginRequest $request)
    {
        // try {
            $credentials = $request->validated();
            $remember = $credentials['remember'] ?? false;
            unset($credentials['remember']);

            if (!Auth::attempt($credentials)) {
                return response([
                    "errors" =>
                    [
                        'error' => ['The Provided credentials are not correct']
                    ]
                ]
                , 422);
            }
            $user = Auth::user();
            // $user =  User::where('email', $request['email'])->first();

            if (!Hash::check($request['password'], $user->password)) {
                return response()->json([
                    "errors" =>
                    [
                        'error' => ['Password not match']
                    ]
                ], 422);
            }
            $token = $user->createToken($user->email)->plainTextToken;

            return response([
                'user' => $user,
                'token' => $token
            ]);
        // } catch (Exception $error) {
        //     return response()->json([
        //         'status_code' => 500,
        //         'message' => 'Error in login',
        //         'error' => $error,
        //     ]);
        // }
    }

    public function logout(Request $request)
    {
        try {
            Auth::user()->tokens()->delete();
            return response()->json([
                'message' => 'Logout successfull',
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in logout',
                'error' => $error,
            ]);
        }
    }
    public function me(Request $request)
    {
        return $request->user();
    }
}
