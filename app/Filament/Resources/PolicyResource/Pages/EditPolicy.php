<?php

namespace App\Filament\Resources\PolicyResource\Pages;

use App\Filament\Resources\PolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPolicy extends EditRecord
{
    protected static string $resource = PolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        Cache::has("policiesKvkk") ?? Cache::forget("policiesKvkk");
        Cache::has("policiesPrivacy") ?? Cache::forget("policiesPrivacy");
        Cache::has("policies") ?? Cache::forget("policies");
    }
}
