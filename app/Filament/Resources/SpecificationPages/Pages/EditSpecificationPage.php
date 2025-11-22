<?php

namespace App\Filament\Resources\SpecificationPages\Pages;

use App\Filament\Resources\SpecificationPages\SpecificationPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecificationPage extends EditRecord
{
    protected static string $resource = SpecificationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
