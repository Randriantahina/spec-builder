<?php

namespace App\Filament\Resources\SpecificationPages\Pages;

use App\Filament\Resources\SpecificationPages\SpecificationPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpecificationPages extends ListRecords
{
    protected static string $resource = SpecificationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
