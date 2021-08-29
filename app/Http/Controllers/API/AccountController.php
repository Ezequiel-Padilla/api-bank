<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AccountController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Account::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Account::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account =  Account::find($id);
        $fields = $request->validate([
            'name' => 'string',
            'email' => 'string|',
            'amount' => 'integer',
            'password' => 'string|confirmed'
        ]);

        $account->update([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'amount' => $fields['amount'],
            'password' => bcrypt($fields['password'])
        ]);
        $account->save();
        return $account;
    }
}
