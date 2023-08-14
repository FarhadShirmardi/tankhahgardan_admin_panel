<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserStatusResource\Pages;
use App\Models\UserStatus;
use Filament\Resources\Resource;

class UserStatusResource extends Resource
{
    protected static ?string $model = UserStatus::class;

    protected static ?string $slug = 'user-status-report';

    protected static function getNavigationGroup(): ?string
    {
        return __('names.finance reports');
    }

    public static function getModelLabel(): string
    {
        return __('filament::pages/user-status.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::pages/user-status.title');
    }

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'fluentui-task-list-square-person-20-o';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserStatus::route('/'),
        ];
    }
}
