<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Events\TransactionCreated;
use App\Models\Transaction;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function handleRecordCreation(array $data): Transaction
    {
        $transaction = Transaction::create($data);
        TransactionCreated::dispatch($transaction);

        return $transaction;
    }
}
