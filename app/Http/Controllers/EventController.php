<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class eventController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $event = event::select('source', 'destiny', 'type', 'amount')
                ->get();

            return $this->sendResponse($event, "eventos obtenidos correctamente");
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
            $event = new event();
            $event->source = $request->input('origen');
            $event->destiny = $request->input('destino');
            $event->type = $request->input('tipo');
            $event->amount = $request->input('monto');
            $Account_source = Account::find($request->input('origen'));
            $event->createToken('auth_token')->plainTextToken;

            switch ($request->input('tipo')) {
                case 'retiro':
                    try {
                        if (!auth()->attempt($token)) {
                            return response()->json([
                                'message' => 'The given data was invalid.',
                                'errors' => [
                                    'password' => [
                                        'Invalid credentials'
                                    ],
                                ]
                            ], 422);
                        } else {

                        }
                        if ($Account_source->amount >= $request->input('monto')) {
                            $Account_source->amount = $Account_source->amount - $request->input('monto');
                            
                            return response()->json([
                                'access_token' => $token,
                                'token_type' => 'Bearer',
                            ]);
                            $Account_source->save();
                            $event->save();
                            return $this->sendResponse($event, "evento creado correctamente y Retiro confirmado");
                        } else {
                            return $this->sendError('La cuenta no tiene suficiente monto');
                        }
                    } catch (\Exception $e) {
                        return $this->sendError($e, "Error al realizar el Retiro", 200);
                    }
                    break;

                case 'transferir':
                    try {
                        $Account_destiny = Account::find($request->input('destino'));

                        if ($Account_source->amount >= $request->input('monto')) {
                            $Account_source->amount = $Account_source->amount - $request->input('monto');
                            $Account_destiny->amount = $Account_destiny->amount + $request->input('monto');
                            $token = $event->createToken('auth_token')->plainTextToken;
                            return response()->json([
                                'access_token' => $token,
                                'token_type' => 'Bearer',
                            ]);
                            $Account_source->save();
                            $Account_destiny->save();
                            $event->save();
                            return $this->sendResponse($event, "evento creado correctamente y Transferencia confirmado");
                        } else {
                            return $this->sendError('La cuenta origen no tiene suficiente monto');
                        }
                    } catch (\Exception $e) {
                        return $this->sendError($e, "Error al realizar la Transferencia", 200);
                    }
                    break;

                case 'deposito':
                    try {
                        $Account_source->amount = $Account_source->amount + $request->input('monto');
                        $Account_source->save();
                        $event->save();
                        return $this->sendResponse($event, "evento creado correctamente y Deposito confirmado");
                    } catch (\Exception $e) {
                        return $this->sendError($e, "Error al realizar el Deposito", 200);
                    }
                    break;
                default:
                    return $this->sendError('El tipo de evento no existe, los eventos disponibles son Deposito, Transferir y Retiro');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al crear el evento", 200);
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
            $event = event::where('id', $id)
                ->select('id', 'source', 'destiny', 'type', 'amount')
                ->get();
            return $this->sendResponse($event, "evento obtenido correctamente");
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al obtener el evento");
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
