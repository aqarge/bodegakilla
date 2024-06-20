<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Totaldebt;

class UpdateDebtState
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
    public function handle(Totaldebt $totaldebt): void
    {
        if ($totaldebt->total_amount == 0.00 && $totaldebt->state_debt != 1) {
            $totaldebt->state_debt = 1; // Cambia el estado de la deuda a "Pagado"
            $totaldebt->save(); }
    }
}
