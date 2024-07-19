<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected static function afterCreate():void{
        if (Cache::has("allCat")) {
           Cache::forget("allCat"); 
        }
    }
}
