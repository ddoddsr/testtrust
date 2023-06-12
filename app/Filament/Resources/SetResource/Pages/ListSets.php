<?php

namespace App\Filament\Resources\SetResource\Pages;

use App\Filament\Resources\SetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSets extends ListRecords
{
    protected static string $resource = SetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
