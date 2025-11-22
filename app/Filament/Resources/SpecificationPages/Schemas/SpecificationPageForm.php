<?php

namespace App\Filament\Resources\SpecificationPages\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Models\Project;

class SpecificationPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informations de la spécification')
                    ->description('Renseignez le titre et sélectionnez le projet associé.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre de la spécification')
                            ->required()
                            ->maxLength(255),
                        Select::make('project_id')
                            ->label('Projet')
                            ->relationship('project', 'name', fn ($query) => $query->where('user_id', auth()->id()))
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
