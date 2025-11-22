<?php

namespace App\Filament\Resources\SpecificationPages\Pages;

use App\Filament\Resources\SpecificationPages\SpecificationPageResource;
use App\Models\SpecificationPage;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class BuilderSpecificationPage extends EditRecord
{
    protected static string $resource = SpecificationPageResource::class;

    public SpecificationPage $specification;

    public array $content = [];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->specification = $this->record;
        $this->content = $this->record->content ?? [];
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->title ?? 'Éditeur de spécification';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('saveContent')
                ->label('Enregistrer')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('saveContent'),
            DeleteAction::make(),
        ];
    }

    public function addBlock(string $type): void
    {
        $block = match($type) {
            'heading' => [
                'type' => 'heading',
                'data' => [
                    'text' => '',
                    'level' => 'h2',
                ],
            ],
            'paragraph' => [
                'type' => 'paragraph',
                'data' => [
                    'text' => '',
                ],
            ],
            'table' => [
                'type' => 'table',
                'data' => [
                    'headers' => ['', ''],
                    'rows' => [
                        ['', ''],
                        ['', ''],
                    ],
                ],
            ],
            'section' => [
                'type' => 'section',
                'data' => [
                    'title' => '',
                    'description' => '',
                ],
            ],
            default => null,
        };

        if ($block) {
            $this->content[] = $block;
        }
    }

    public function removeBlock(int $index): void
    {
        unset($this->content[$index]);
        $this->content = array_values($this->content);
    }

    public function updateBlock(int $index, array $data): void
    {
        if (isset($this->content[$index])) {
            $this->content[$index]['data'] = array_merge($this->content[$index]['data'], $data);
        }
    }

    public $editingBlockIndex = null;
    public $editingBlockData = [];

    public function startEditing(int $index): void
    {
        $this->editingBlockIndex = $index;
        $this->editingBlockData = $this->content[$index]['data'] ?? [];
    }

    public function cancelEditing(): void
    {
        $this->editingBlockIndex = null;
        $this->editingBlockData = [];
    }

    public function saveBlock(): void
    {
        if ($this->editingBlockIndex !== null && isset($this->content[$this->editingBlockIndex])) {
            $this->content[$this->editingBlockIndex]['data'] = $this->editingBlockData;
            $this->cancelEditing();
        }
    }

    public function saveContent(): void
    {
        $this->record->update([
            'content' => $this->content,
        ]);

        \Filament\Notifications\Notification::make()
            ->title('Spécification enregistrée')
            ->success()
            ->send();
    }

    public function getView(): string
    {
        return 'filament.resources.specification-pages.pages.builder-specification-page';
    }
}
