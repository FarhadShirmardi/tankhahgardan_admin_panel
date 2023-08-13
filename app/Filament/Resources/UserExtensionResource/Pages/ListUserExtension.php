<?php

namespace App\Filament\Resources\UserExtensionResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Filament\Resources\UserExtensionResource;
use Filament\Resources\Pages\Page;

class ListUserExtension extends Page
{
    protected static string $resource = UserExtensionResource::class;

    protected static string $view = 'filament.resources.user-extension-resource.pages.report';

    protected function getTitle(): string
    {
        return __('filament::pages/user-extension.single title');
    }
}
