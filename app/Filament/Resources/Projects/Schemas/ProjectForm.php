<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Informations du projet')
                    ->description('Renseignez les informations principales concernant le projet.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom du projet')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Description du projet')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        DatePicker::make('start_date')
                            ->label('Date de début')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->after('start_date'),
                    ]),
            ]);
    }
}
