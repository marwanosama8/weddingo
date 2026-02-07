<?php

namespace App\Filament\Resources\PartnerRequestResource\Pages;

use App\Filament\Resources\PartnerRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerRequests extends ListRecords
{
    protected static string $resource = PartnerRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
