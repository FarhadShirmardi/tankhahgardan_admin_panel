<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserExtensionResource\Pages;
use App\Models\UserStatusLog;
use Filament\Resources\Resource;

class UserExtensionResource extends Resource
{
    protected static ?string $model = UserStatusLog::class;

    protected static ?string $slug = 'user-extension-report';

    public static function getModelLabel(): string
    {
        return __('filament::pages/user-extension.single title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/user-extension.title');
    }

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'fluentui-arrow-trending-down-24-o';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserExtension::route('/'),
        ];
    }
}
