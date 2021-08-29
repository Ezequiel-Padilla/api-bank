<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:accounts,email',
            'amount' => 'required|integer',
            'password' => 'required|string|confirmed'
        ]);

        $account = Account::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'amount' => $fields['amount'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $account->createToken('account_token')->plainTextToken;

        $response = [
            'account' => $account,
            'token' => $token
        ];

        Mail::to($account->email)->send(new \App\Mail\AccountMail($response));
        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|',
            'password' => 'required|string|'
        ]);

        $account = Account::where('email', $fields['email'])->first();

        if (!$account || !Hash::check($fields['password'], $account->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $account->createToken('account_token')->plainTextToken;

        $response = [
            'account' => $account,
            'token' => $token
        ];

        Mail::to($account->email)->send(new \App\Mail\AccountMail($response));
        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
