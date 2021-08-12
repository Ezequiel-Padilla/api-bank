<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $Account = Account::where('active', '=', 1)
                ->select('id', 'amount', 'email')
                ->get();

            return $this->sendResponse($Account, "Cuantas obtenidas correctamente");
        } catch (\Exception $e) {
            return $this->sendError($e, "Error controlado", 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $Account = new Account();
            $Account->amount = $request->input('monto');
            $Account->email = $request->input('email');
            $Account->password = $request->input('contraseña');
            $Account->save();
            return $this->sendResponse($Account, "Cuanta creada correctamente");
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al crear la Cuenta", 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $Account = Account::where('id', $id)
                ->select('id', 'amount', 'email', 'active')
                ->get();
            return $this->sendResponse($Account, "Cuenta obtenida correctamente");
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al obtener la Cuenta");
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
