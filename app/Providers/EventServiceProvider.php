<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TransactionCreated;
use App\Listeners\UpdateBoxTotals;
use App\Events\DebtCreated;
use App\Events\DebtUpdated;
use App\Events\DebtDeleted;
use App\Listeners\UpdateTotalDebtAmount;
use App\Events\PaymentCreated;
use App\Events\PaymentUpdated;
use App\Events\PaymentDeleted;
use App\Listeners\UpdatePayments;
use App\Listeners\UpdateDebtState;
use App\Observers\TotaldebtObserver;
use App\Models\Totaldebt;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TransactionCreated::class => [
            UpdateBoxTotals::class,
        ],
        DebtCreated::class => [
            UpdateTotalDebtAmount::class,
        ],
        DebtUpdated::class => [
            UpdateTotalDebtAmount::class,
        ],
        DebtDeleted::class => [
            UpdateTotalDebtAmount::class,
        ],
        PaymentCreated::class => [
            UpdatePayments::class  
        ],
        PaymentUpdated::class => [
            UpdatePayments::class  
        ],
        PaymentDeleted::class => [
            UpdatePayments::class 
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        Totaldebt::observe(TotaldebtObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
