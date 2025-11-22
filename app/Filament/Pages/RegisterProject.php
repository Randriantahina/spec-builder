<?php

namespace App\Filament\Pages;

use App\Models\Project;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterProject extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Créer un projet';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nom du projet')
                    ->required(),
                Textarea::make('description')
                    ->label('Description'),
            ]);
    }

    protected function handleRegistration(array $data): Project
    {
        $data['user_id'] = auth()->id();
        $project = Project::create($data);

        return $project;
    }
}
