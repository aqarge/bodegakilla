<?php

namespace App\Observers;

use App\Models\Totaldebt;

class TotaldebtObserver
{
    /**
     * Handle the Totaldebt "created" event.
     */
    public function created(Totaldebt $totaldebt): void
    {
        //
    }

    /**
     * Handle the Totaldebt "updated" event.
     */
    public function updated(Totaldebt $totaldebt): void
    {
        //
    }

    /**
     * Handle the Totaldebt "deleted" event.
     */
    public function deleted(Totaldebt $totaldebt): void
    {
        //
    }

    /**
     * Handle the Totaldebt "restored" event.
     */
    public function restored(Totaldebt $totaldebt): void
    {
        //
    }

    /**
     * Handle the Totaldebt "force deleted" event.
     */
    public function forceDeleted(Totaldebt $totaldebt): void
    {
        //
    }

    public function saving(Totaldebt $totaldebt)
    {
        // Actualizar el estado de la deuda basado en el monto total
        if ($totaldebt->total_amount <= 0) {
            $totaldebt->state_debt = 1; // Pagado
        } else {
            $totaldebt->state_debt = 0; // Falta pagar
        }

        // Actualizar el riesgo basado en el monto total
        if ($totaldebt->total_amount >= 0 && $totaldebt->total_amount <= 30) {
            $totaldebt->risk = 'baja'; // Baja
        } elseif ($totaldebt->total_amount >= 31 && $totaldebt->total_amount <= 80) {
            $totaldebt->risk = 'moderada'; // Moderada
        } elseif ($totaldebt->total_amount > 80) {
            $totaldebt->risk = 'alta'; // Alta
        }
    }
}
