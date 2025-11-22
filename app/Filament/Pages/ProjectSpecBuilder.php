<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\SpecificationPage;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ProjectSpecBuilder extends Page
{
    protected static ?string $slug = 'project-spec-builder';
    protected string $view = 'filament.pages.project-spec-builder';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    public Project $project;
    public Collection $specificationPages;
    public ?SpecificationPage $currentPage = null;
    public array $blocks = [];

    public $editingBlockId = null;
    public $editingBlockData = [];

    public $newPageTitle = '';

    public function mount($projectId = null, $pageId = null): void
    {
        // Récupérer projectId depuis la query string si pas en paramètre
        if (!$projectId) {
            $projectId = request()->query('projectId');
        }

        if (!$projectId) {
            abort(404, 'Project ID is required');
        }
        $this->project = Project::findOrFail($projectId);
        $this->loadPages();

        if ($pageId) {
            $this->currentPage = $this->specificationPages->find($pageId);
        } else {
            $this->currentPage = $this->specificationPages->first();
        }

        // Créer la première page si aucune n'existe
        if (!$this->currentPage) {
            $this->createNewPage('Page 1');
        } else {
            $this->blocks = $this->currentPage->content ?? [];
        }
    }
    // ✅ Méthode helper pour générer l'URL
    public static function getUrlForProject(int $projectId): string
    {
        $panel = \Filament\Facades\Filament::getCurrentPanel();
        $panelPath = $panel->getPath();
        return url("{$panelPath}/project-spec-builder?projectId={$projectId}");
    }

    public function getTitle(): string | Htmlable
    {
        return $this->project->name . ' - Spécification';
    }

    protected function loadPages(): void
    {
        $this->specificationPages = $this->project->specificationPages()->ordered()->get();
    }

    // ========== GESTION DES PAGES ==========

    public function createNewPage($title = null): void
    {
        $title = $title ?: $this->newPageTitle ?: 'Nouvelle page';

        $newPage = SpecificationPage::create([
            'project_id' => $this->project->id,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . time(),
            'order' => $this->specificationPages->count(),
            'content' => [],
        ]);

        $this->loadPages();
        $this->switchPage($newPage->id);
        $this->newPageTitle = '';
    }

    public function switchPage($pageId): void
    {
        // Sauvegarder la page actuelle avant de changer
        if ($this->currentPage) {
            $this->saveCurrentPage();
        }

        $this->currentPage = $this->specificationPages->find($pageId);
        $this->blocks = $this->currentPage->content ?? [];
        $this->editingBlockId = null;
        $this->editingBlockData = [];
    }

    public function deletePage($pageId): void
    {
        $page = $this->specificationPages->find($pageId);

        if ($page && $this->specificationPages->count() > 1) {
            $page->delete();
            $this->loadPages();

            // Basculer sur une autre page
            if ($this->currentPage && $this->currentPage->id === $pageId) {
                $this->switchPage($this->specificationPages->first()->id);
            }
        }
    }

    public function renamePage($pageId, $newTitle): void
    {
        $page = $this->specificationPages->find($pageId);
        if ($page && $newTitle) {
            $page->update([
                'title' => $newTitle,
                'slug' => Str::slug($newTitle) . '-' . $page->id,
            ]);
            $this->loadPages();
        }
    }

    // ========== GESTION DES BLOCS ==========

    public function addBlock(string $type): void
    {
        $block = [
            'id' => uniqid('block_'),
            'type' => $type,
            'data' => $this->getDefaultBlockData($type),
        ];

        $this->blocks[] = $block;
        $this->saveCurrentPage();
    }

    public function removeBlock(string $blockId): void
    {
        $this->blocks = array_values(array_filter($this->blocks, fn ($b) => $b['id'] !== $blockId));
        $this->saveCurrentPage();
    }

    public function startEditing(string $blockId): void
    {
        $block = collect($this->blocks)->firstWhere('id', $blockId);
        if ($block) {
            $this->editingBlockId = $blockId;
            $this->editingBlockData = $block['data'] ?? [];
        }
    }

    public function cancelEditing(): void
    {
        $this->editingBlockId = null;
        $this->editingBlockData = [];
    }

    public function saveBlock(): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $this->editingBlockId);
        if ($index !== false) {
            $this->blocks[$index]['data'] = $this->editingBlockData;
            $this->editingBlockId = null;
            $this->editingBlockData = [];
            $this->saveCurrentPage();
        }
    }

    // ========== GESTION DES TABLEAUX ==========

    public function addTableColumn(string $blockId): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false && $this->blocks[$index]['type'] === 'table') {
            $this->blocks[$index]['data']['headers'][] = 'Nouvelle colonne';
            foreach ($this->blocks[$index]['data']['rows'] as &$row) {
                $row[] = '';
            }
            $this->saveCurrentPage();
        }
    }

    public function removeTableColumn(string $blockId, int $columnIndex): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false && $this->blocks[$index]['type'] === 'table') {
            unset($this->blocks[$index]['data']['headers'][$columnIndex]);
            $this->blocks[$index]['data']['headers'] = array_values($this->blocks[$index]['data']['headers']);

            foreach ($this->blocks[$index]['data']['rows'] as &$row) {
                unset($row[$columnIndex]);
                $row = array_values($row);
            }
            $this->saveCurrentPage();
        }
    }

    public function addTableRow(string $blockId): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false && $this->blocks[$index]['type'] === 'table') {
            $columnCount = count($this->blocks[$index]['data']['headers']);
            $this->blocks[$index]['data']['rows'][] = array_fill(0, $columnCount, '');
            $this->saveCurrentPage();
        }
    }

    public function removeTableRow(string $blockId, int $rowIndex): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false && $this->blocks[$index]['type'] === 'table') {
            unset($this->blocks[$index]['data']['rows'][$rowIndex]);
            $this->blocks[$index]['data']['rows'] = array_values($this->blocks[$index]['data']['rows']);
            $this->saveCurrentPage();
        }
    }

    public function updateTableCell(string $blockId, int $rowIndex, int $colIndex, string $value): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false) {
            $this->blocks[$index]['data']['rows'][$rowIndex][$colIndex] = $value;
            $this->saveCurrentPage();
        }
    }

    public function updateTableHeader(string $blockId, int $colIndex, string $value): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false) {
            $this->blocks[$index]['data']['headers'][$colIndex] = $value;
            $this->saveCurrentPage();
        }
    }

    // ========== GESTION DES LISTES ==========

    public function addListItem(string $blockId): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false && $this->blocks[$index]['type'] === 'list') {
            $this->blocks[$index]['data']['items'][] = '';
            $this->saveCurrentPage();
        }
    }

    public function removeListItem(string $blockId, int $itemIndex): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false && $this->blocks[$index]['type'] === 'list') {
            unset($this->blocks[$index]['data']['items'][$itemIndex]);
            $this->blocks[$index]['data']['items'] = array_values($this->blocks[$index]['data']['items']);
            $this->saveCurrentPage();
        }
    }

    public function updateListItem(string $blockId, int $itemIndex, string $value): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index !== false) {
            $this->blocks[$index]['data']['items'][$itemIndex] = $value;
            $this->saveCurrentPage();
        }
    }

    // ========== HELPERS ==========

    private function getDefaultBlockData(string $type): array
    {
        return match($type) {
            'heading' => ['text' => '', 'level' => 'h2'],
            'paragraph' => ['text' => ''],
            'table' => [
                'headers' => ['Colonne 1', 'Colonne 2'],
                'rows' => [['', ''], ['', '']],
            ],
            'list' => ['items' => ['', '']],
            default => [],
        };
    }

    private function saveCurrentPage(): void
    {
        if ($this->currentPage) {
            $this->currentPage->update(['content' => $this->blocks]);
        }
    }
}
