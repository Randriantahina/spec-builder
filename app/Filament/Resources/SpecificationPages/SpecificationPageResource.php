<?php

namespace App\Filament\Resources\SpecificationPages;

use App\Filament\Resources\SpecificationPages\Pages\CreateSpecificationPage;
use App\Filament\Resources\SpecificationPages\Pages\EditSpecificationPage;
use App\Filament\Resources\SpecificationPages\Pages\ListSpecificationPages;
use App\Filament\Resources\SpecificationPages\Schemas\SpecificationPageForm;
use App\Filament\Resources\SpecificationPages\Tables\SpecificationPagesTable;
use App\Models\SpecificationPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpecificationPageResource extends Resource
{
    protected static ?string $model = SpecificationPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SpecificationPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpecificationPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpecificationPages::route('/'),
            'create' => CreateSpecificationPage::route('/create'),
            'edit' => EditSpecificationPage::route('/{record}/edit'),
        ];
    }
}
