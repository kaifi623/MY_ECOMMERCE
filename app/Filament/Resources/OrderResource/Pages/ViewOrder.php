<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use Filament\Actions\EditAction;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(), 
        ];
    }
}
