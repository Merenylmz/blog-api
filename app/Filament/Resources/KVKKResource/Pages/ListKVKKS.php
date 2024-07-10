<?php

namespace App\Filament\Resources\KVKKResource\Pages;

use App\Filament\Resources\KVKKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKVKK extends ListRecords
{
    protected static string $resource = KVKKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
