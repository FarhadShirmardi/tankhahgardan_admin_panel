<?php

namespace App\Filament\Resources\PanelUserResource\Pages;

use App\Filament\Resources\PanelUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePanelUser extends CreateRecord
{
    protected static string $resource = PanelUserResource::class;

    protected function getRedirectUrl(): string
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return self::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['phone_number'] = formatPhoneNumber(englishString($data['phone_number']));

        return $data;
    }
}
