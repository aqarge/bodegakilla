<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Models\Box;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBoxTotals
{
    public function handle(TransactionCreated $event)
    {
        $transaction = $event->transaction;
        $box = $transaction->boxes;

        // Verifica si es una transacción de saldo inicial
        if ($transaction->type_tran === 'inicial') {
            $box->inbalance = $transaction->amount_tran;
        } elseif ($transaction->type_tran === 'ingreso') {
            $box->income += $transaction->amount_tran;
        } elseif ($transaction->type_tran === 'egreso') {
            $box->expenses += $transaction->amount_tran;
        }

        // Calcula el saldo del día
        $box->revenue = $box->income - $box->expenses;

        // Calcula el saldo total
        $box->tobalance = $box->inbalance + $box->income - $box->expenses;

        // Guarda los cambios en la base de datos
        $box->save();
    }
}
