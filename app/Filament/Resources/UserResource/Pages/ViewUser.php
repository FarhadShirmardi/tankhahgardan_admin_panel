<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Page;

class ViewUser extends Page
{
    public User $user;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.view-user';

    protected function getTitle(): string
    {
        return $this->user->formatted_username;
    }

    public function mount(int $record): void
    {
        $this->user = User::findOrFail($record);
    }

    public function updateUser(): void
    {
        $this->user->updateUserReport();
    }
}
