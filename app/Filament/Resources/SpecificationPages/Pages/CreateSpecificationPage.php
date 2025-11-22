<?php

namespace App\Filament\Resources\SpecificationPages\Pages;

use App\Filament\Resources\SpecificationPages\SpecificationPageResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateSpecificationPage extends CreateRecord
{
    protected static string $resource = SpecificationPageResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Créer une spécification';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('builder', ['record' => $this->record]);
    }
}
