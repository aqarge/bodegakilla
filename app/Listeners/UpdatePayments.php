<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PaymentCreated;
use App\Events\PaymentUpdated;
use App\Events\PaymentDeleted;
use App\Models\Totaldebt;
use App\Models\Payment;

class UpdatePayments
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
        $totaldebt = $event->payment->totaldebt;
       $total_amount = $totaldebt->debts()->sum('total_debt') - $totaldebt->payments()->sum('pay');
        $totaldebt->total_amount = $total_amount;
        $totaldebt->save();
    }
    public function handlePaymentCreated(PaymentCreated $event): void
    {
        $this->updateTotalDebtAmount($event->payment->totaldebt);
    }

    /**
     * Handle the event when a payment is updated.
     */
    public function handlePaymentUpdated(PaymentUpdated $event): void
    {
        $this->updateTotalDebtAmount($event->payment->totaldebt);
    }

    /**
     * Handle the event when a payment is deleted.
     */
    public function handlePaymentDeleted(PaymentDeleted $event): void
    {
        $this->updateTotalDebtAmount($event->payment->totaldebt);
    }

    /**
     * Update the total debt amount for the given totaldebt.
     */
    private function updateTotalDebtAmount(Totaldebt $totaldebt): void
    {
        $total_amount = $totaldebt->debts()->sum('total_debt') - $totaldebt->payments()->sum('pay');
        $totaldebt->total_amount = $total_amount;
        $totaldebt->save();
    }
  
}
