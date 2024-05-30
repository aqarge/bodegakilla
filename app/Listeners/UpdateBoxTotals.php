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

        if ($transaction->type_tran === 'ingreso') {
            $box->income += $transaction->amount_tran;
        } elseif ($transaction->type_tran === 'egreso') {
            $box->expenses += $transaction->amount_tran;
        }

        $box->revenue = $box->income - $box->expenses;
        $box->save();
    }
}
