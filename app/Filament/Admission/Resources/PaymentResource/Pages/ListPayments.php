<?php

namespace App\Filament\Admission\Resources\PaymentResource\Pages;

use App\Filament\Admission\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;
}
