<?php

namespace App\Filament\Resources\Admission\RegistrantPaymentResource\Pages;

use App\Filament\Resources\Admission\RegistrantPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Number;

class EditRegistrantPayment extends EditRecord
{
    protected static string $resource = RegistrantPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_receipt')
                ->label(__('Send Receipt via WA'))
                ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                ->color('gray')
                ->url(function () {
                    $amount = Number::currency($this->record->amount, in: 'IDR', locale: 'id');
                    if (isset($this->record->registrant)) {
                        $message = urlencode("Halo {$this->record->registrant->user->name}\n\nPembayaran kamu sebesar {$amount} telah diterima oleh {$this->record->receiver->user->name} pada hari {$this->record->created_at->isoFormat('LLLL')}.\nNomor pembayaran: {$this->record->code}\n\nTerima kasih.");
                        return "https://wa.me/" . filter_var($this->record->registrant->phone, FILTER_SANITIZE_NUMBER_INT) . "?text=" . $message;
                    }
                })->openUrlInNewTab(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if (isset($this->record->registrant)) {
            $is_paid_off = $this->record->registrant->bills->load('items')->pluck('items.*.amount')->flatten()->sum() <= $this->record->registrant->payments->sum('amount');

            $this->record->registrant->update([
                'paid_at' => $is_paid_off ? now() : null,
            ]);
        }
    }
}
