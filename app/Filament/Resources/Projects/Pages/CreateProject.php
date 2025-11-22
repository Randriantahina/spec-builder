<?php

// app/Filament/Resources/Projects/Pages/CreateProject.php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Models\SpecificationPage;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Créer automatiquement la première page de spécification
        $specPage = SpecificationPage::create([
            'project_id' => $this->record->id,
            'title' => 'Page 1',
            'slug' => 'page-1-' . $this->record->id,
            'order' => 0,
            'content' => [],
        ]);

        // Rediriger vers le builder avec le bon format
        return '/app/projects/' . $this->record->id . '/spec';
    }
}
