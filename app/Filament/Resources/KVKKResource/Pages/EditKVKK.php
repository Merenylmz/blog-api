<?php

namespace App\Filament\Resources\KVKKResource\Pages;

use App\Filament\Resources\KVKKResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKVKK extends EditRecord
{
    protected static string $resource = KVKKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
