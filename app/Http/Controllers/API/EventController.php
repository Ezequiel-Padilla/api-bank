<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Models\Event;
use Illuminate\Http\Request;
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

        switch (strtolower($request->input('type'))) {
            case 'retiro':
                return $this->withdrawal($request);
                break;

            case 'transferir':
                return $this->transfer($request);
                break;

            case 'deposito':
                return $this->deposit($request);
                break;
            default:
                return $this->sendError('Error de tipo', 'El tipo de evento no existe, los eventos disponibles son deposito, transferir y retiro');
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
        return Event::find($id);
    }

    private function withdrawal($request)
    {
        try {
            $account_source = Account::find($request->input('source'));

            if ($account_source->amount >= $request->input('amount') && $request->input('amount') > 1000) {
                $account_source->amount = $account_source->amount - $request->input('amount');

                $event = Event::create($request->all());

                $account_source->save();
                $event->save();

                return $this->sendResponse($event, 'evento creado correctamente y retiro confirmado', 200);
            } else {
                return $this->sendError('Error monto', 'La cuenta no tiene suficiente monto');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, 'Error al realizar el retiro');
        }
    }

    private function transfer($request)
    {
        try {
            $account_source = Account::find($request->input('source'));
            $account_destiny = Account::find($request->input('destiny'));

            if ($account_source->amount >= $request->input('amount') && $request->input('amount') > 1000) {
                $account_source->amount = $account_source->amount - $request->input('amount');
                $account_destiny->amount = $account_destiny->amount + $request->input('amount');

                $event = Event::create($request->all());

                $account_source->save();
                $account_destiny->save();
                $event->save();

                return $this->sendResponse($event, 'evento creado correctamente y transferencia confirmada');
            } else {
                return $this->sendError('Error de monto', 'La cuenta origen no tiene suficiente monto');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, 'Error al realizar la transferencia');
        }
    }

    private function deposit($request)
    {
        try {
            $account_source = Account::find($request->input('source'));
            $account_source->amount = $account_source->amount + $request->input('amount');

            $event = Event::create($request->all());

            $account_source->save();
            $event->save();

            return $this->sendResponse($event, 'evento creado correctamente y deposito confirmado');
        } catch (\Exception $e) {
            return $this->sendError($e, 'Error al realizar el deposito');
        }
    }
}
