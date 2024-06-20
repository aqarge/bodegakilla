<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Events\TransactionUpdated;
use App\Models\Box;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBoxTotals
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // Llama al método adecuado basado en el tipo de evento
        if ($event instanceof TransactionCreated) {
            $this->handleTransactionCreated($event);
        } elseif ($event instanceof TransactionUpdated) {
            $this->handleTransactionUpdated($event);
        }
    }

    /**
     * Handle the event when a transaction is created.
     */
    public function handleTransactionCreated(TransactionCreated $event): void
    {
        $this->updateBoxTotals($event->transaction, 'create');
    }

    /**
     * Handle the event when a transaction is updated.
     */
    public function handleTransactionUpdated(TransactionUpdated $event): void
    {
        $this->updateBoxTotals($event->transaction, 'update');
    }

    /**
     * Update the box totals for the given transaction.
     */
    private function updateBoxTotals(Transaction $transaction, string $action): void
    {
        $box = $transaction->boxes;

        // Si la transacción es una actualización, primero revertir los cambios anteriores
        if ($action === 'update') {
            $original = $transaction->getOriginal();

            if ($original['type_tran'] === 'inicial') {
                $box->inbalance -= $original['amount_tran'];
            } elseif ($original['type_tran'] === 'ingreso') {
                $box->income -= $original['amount_tran'];
            } elseif ($original['type_tran'] === 'egreso') {
                $box->expenses -= $original['amount_tran'];
            }
        }

        // Verifica si es una transacción de saldo inicial
        if ($transaction->type_tran === 'inicial') {
            $box->inbalance += $transaction->amount_tran;
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
