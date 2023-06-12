<?php

namespace App\Filament\Resources\SetResource\Pages;

use App\Filament\Resources\SetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSet extends EditRecord
{
    protected static string $resource = SetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
