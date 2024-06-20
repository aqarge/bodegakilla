<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\DebtCreated;
use App\Events\DebtUpdated;
use App\Events\DebtDeleted;
use App\Models\Totaldebt;

class UpdateTotalDebtAmount
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
        $totaldebt = $event->debt->totaldebt;
        $total_amount = $totaldebt->debts()->sum('total_debt') - $totaldebt->payments()->sum('pay');
        $totaldebt->total_amount = $total_amount;
        $totaldebt->save();
    }
}
