<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use App\Models\UniqueId;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Str;
class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function afterCreate(): void
    {
        $save = UniqueId::create([
            'subscription_id' => $this->record->id,
            'unique_id' => $this->record->id . '_' . time() . '_' . Str::random(6)
        ]);
    }

}
