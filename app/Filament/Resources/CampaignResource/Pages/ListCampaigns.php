<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampaigns extends ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
