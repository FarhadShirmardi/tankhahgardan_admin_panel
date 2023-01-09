<?php

namespace App\Filament\Resources\PanelUserResource\Pages;

use App\Filament\Resources\PanelUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPanelUser extends EditRecord
{
    protected static string $resource = PanelUserResource::class;

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['phone_number'] = formatPhoneNumber(englishString($data['phone_number']));

        return $data;
    }

    protected function getActions(): array
    {
        return [
        ];
    }
}
