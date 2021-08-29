<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;


class EventController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'source' => 'required',
            'destiny' => 'nullable',
            'type' => 'required',
            'amount' => 'required'
        ]);

        $event = Event::create($request->all());
        $account_source = Account::find($event->source);
        $token = $event->createToken('eventToken')->plainTextToken;

        $response = [
            'event' => $event,
            'token' => $token
        ];

        switch (strtolower($request->input('type'))) {
            case 'retiro':
                $this->retiro($account_source, $event, $request);
                break;

            case 'transferir':
                $this->transferir($account_source, $event, $request);
                break;

            case 'deposito':
                $this->deposito($account_source, $event, $request);
                break;
            default:
                return $this->sendError('El tipo de evento no existe, los eventos disponibles son Deposito, Transferir y Retiro');
        }
        $event->tokens()->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Event::find($id);
    }

    private function retiro($account_source, $event, $request) {
        try {
            if ($account_source->amount >= $request->input('amount') && $request->input('amount') > 1000) {
                $account_source->amount = $account_source->amount - $request->input('amount');

                $account_source->save();
                $event->save();
                return $this->sendResponse($event, "evento creado correctamente y Retiro confirmado");
            } else {
                return $this->sendError('La cuenta no tiene suficiente monto');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al realizar el Retiro", 200);
        }
    }

    private function transferir($account_source, $event, $request) {
        try {
            $Account_destiny = Account::find($request->input('destiny'));

            if ($account_source->amount >= $request->input('amount') && $request->input('amount') > 1000) {
                $account_source->amount = $account_source->amount - $request->input('amount');
                $Account_destiny->amount = $Account_destiny->amount + $request->input('amount');

                $account_source->save();
                $Account_destiny->save();
                $event->save();
                return $this->sendResponse($event, "evento creado correctamente y Transferencia confirmado");
            } else {
                return $this->sendError('La cuenta origen no tiene suficiente monto');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al realizar la Transferencia", 200);
        }
    }

    private function deposito($account_source, $event, $request) {
        try {
            $account_source->amount = $account_source->amount + $request->input('amount');

            $account_source->save();
            $event->save();
            return $this->sendResponse($event, "evento creado correctamente y Deposito confirmado");
        } catch (\Exception $e) {
            return $this->sendError($e, "Error al realizar el Deposito", 200);
        }
    }
}
