<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email Field Required',
                 'email.email' => 'Please Enter Email Format',
                 'password.required' => 'Password Field Required',
            ]);

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $user = Auth::user();
            $tokenResult = $user->createToken('Login');
            $response = [
                'status' => true,
                'message' => 'Login Successful',
                'token' => $tokenResult->accessToken,
                'expires_in' => strtotime($tokenResult->token->expires_at),
            ];

            return response()->json($response, 200);

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email or password is incorrect',                'error' => 'unauthorized'
            ], 401);
        }
    }

    public function register(Request $request)
    {

        $request->validate(
            [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'role' => 'required'
            ],
            [
                'email.required' => 'Email Field Required',
                 'email.email' => 'Please Enter Email Format',
                 'email.unique' => 'Email Already Received, Please Enter A Different Email',
                 'password.required' => 'Password Field Required',
                 'password.min' => 'Password must be at least 8 characters.',
                 'role.required' => 'Role Field Required',
            ]);

        $email = $request->email;
        $password = $request->password;
        $role = $request->role;

        $result = User::create([
            'name' => '',
            'email' => $email,
            'role' => $role,
            'password' => bcrypt($password),
        ]);

        if ($result) {
            $response = [
                'status' => true,
                'message' => 'Transaction Successful',            ];
            $status = 200;
        } else {
            $response = [
                'status' => false,
                'message' => 'Transaction Failed',            ];
            $status = 400;
        }

        return response()->json($response, $status);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => true,
            'message' => 'Logout was successful'        ], 200);
    }

    public function getAllUsers(Request $request)
    {
        $result = User::all();
        return response()->json([
            'status' => true,
            'users' => $result->toArray()
        ], 200);
    }

    public function getUsersReports(Request $request)
    {
        $result = User::all()->groupBy('role');
        $response = null;
        foreach ($result->toArray() as $index => $item) {
            $response[] = [
                'name' => $index,
                'y' => count($item)
            ];
        }


        return response()->json([
            'status' => true,
            'title' => 'Reporting of User Groups',            'data' => $response
        ], 200);
    }
}
