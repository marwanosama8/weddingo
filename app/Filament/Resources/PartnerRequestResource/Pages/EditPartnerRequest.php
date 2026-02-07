<?php

namespace App\Filament\Resources\PartnerRequestResource\Pages;

use App\Filament\Resources\PartnerRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerRequest extends EditRecord
{
    protected static string $resource = PartnerRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
