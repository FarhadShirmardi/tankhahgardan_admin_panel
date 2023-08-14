<?php

namespace App\Filament\Resources\UserStatusResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Filament\Resources\UserExtensionResource;
use App\Filament\Resources\UserStatusResource;
use Filament\Resources\Pages\Page;

class ListUserStatus extends Page
{
    protected static string $resource = UserStatusResource::class;

    protected static string $view = 'filament.resources.user-status-resource.pages.report';

    protected function getTitle(): string
    {
        return __('filament::pages/user-status.title');
    }
}
