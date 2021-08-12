<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Event;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\ElseIf_;

class EventController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $Event = Event::select('source', 'destiny', 'type', 'amount')
                ->get();

            return $this->sendResponse($Event, "Eventos obtenidos correctamente");
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
            $Event = new Event();
            $Event->source = $request->input('origen');
            $Event->destiny = $request->input('destino');
            $Event->type = $request->input('tipo');
            $Event->amount = $request->input('monto');

            switch ($request->input('tipo')) {
                case 'retiro':
                    try {
                        $Account = Account::find($request->input('origen'));

                        if ($Account->amount >= $request->input('monto')) {
                            $Account->amount = $Account->amount - $request->input('monto');
                            $Account->save();
                            $Event->save();
                            return $this->sendResponse($Event, "Evento creado correctamente y Retiro confirmado");
                        } else {
                            return $this->sendError('La cuenta no tiene suficiente monto');
                        }
                    } catch (\Exception $e) {
                        return $this->sendError($e, "Error al realizar el Retiro", 200);
                    }
                    break;

                case 'transferir':
                    try {
                        $Account_source = Account::find($request->input('origen'));
                        $Account_destiny = Account::find($request->input('destino'));

                        if ($Account_source->amount >= $request->input('monto')) {
                            $Account_source->amount = $Account_source->amount - $request->input('monto');
                            $Account_destiny->amount = $Account_destiny->amount + $request->input('monto');
                            $Account_source->save();
                            $Account_destiny->save();
                            $Event->save();
                            return $this->sendResponse($Event, "Evento creado correctamente y Transferencia confirmado");
                        } else {
                            return $this->sendError('La cuenta origen no tiene suficiente monto');
                        }
                    } catch (\Exception $e) {
                        return $this->sendError($e, "Error al realizar la Transferencia", 200);
                    }
                    break;

                case 'deposito':
                    try {
                        $Account = Account::find($request->input('origen'));
                        $Account->amount = $Account->amount + $request->input('monto');
                        $Account->save();
                        $Event->save();
                        return $this->sendResponse($Event, "Evento creado correctamente y Deposito confirmado");
                    } catch (\Exception $e) {
                        return $this->sendError($e, "Error al realizar el Deposito", 200);
                    }
                    break;
                default:
                    return $this->sendError('El tipo de evento no existe, los eventos disponibles son Deposito, Transferir y Retiro');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al crear el Evento", 200);
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
            $Event = Event::where('id', $id)
                ->select('id', 'source', 'destiny', 'type', 'amount')
                ->get();
            return $this->sendResponse($Event, "Evento obtenido correctamente");
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al obtener el Evento");
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
