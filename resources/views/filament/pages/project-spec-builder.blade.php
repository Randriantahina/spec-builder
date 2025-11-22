<x-filament-panels::page>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endpush
    <div
        class="flex h-[calc(100vh-8rem)] gap-0 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

        <!-- Sidebar Navigation - Pages -->
        <div
            class="w-72 shrink-0 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border-r border-gray-200/50 dark:border-gray-700/50 flex flex-col shadow-lg">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-1">{{ $project->name }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Spécification</p>
            </div>

            <!-- Liste des pages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                @foreach ($specificationPages as $page)
                    <div class="group flex items-center justify-between p-3 rounded-lg cursor-pointer transition-all duration-200
                               {{ $currentPage && $currentPage->id === $page->id
                                   ? 'bg-primary-100 dark:bg-primary-900/30 border-2 border-primary-500 shadow-sm'
                                   : 'hover:bg-gray-100 dark:hover:bg-gray-700 border-2 border-transparent' }}"
                        wire:click="switchPage({{ $page->id }})">
                        <div class="flex-1 min-w-0">
                            <input type="text" value="{{ $page->title }}"
                                wire:blur="renamePage({{ $page->id }}, $event.target.value)"
                                class="w-full bg-transparent border-none focus:outline-none focus:ring-0 font-medium text-sm text-gray-900 dark:text-gray-100"
                                onclick="event.stopPropagation()" />
                        </div>
                        @if ($specificationPages->count() > 1)
                            <button wire:click.stop="deletePage({{ $page->id }})"
                                class="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 ml-2 p-1 rounded transition-all"
                                title="Supprimer la page">
                                <x-heroicon-o-trash class="w-4 h-4" />
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Bouton ajouter page -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex gap-2">
                    <input type="text" wire:model="newPageTitle" wire:keydown.enter="createNewPage()"
                        placeholder="Nouvelle page..."
                        class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" />
                    <button wire:click="createNewPage()"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                        title="Créer une nouvelle page">
                        <x-heroicon-o-plus class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Zone principale -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @if ($currentPage)
                <!-- Zone d'édition -->
                <div class="flex-1 overflow-y-auto">
                    <div class="max-w-5xl mx-auto px-12 py-16">
                        <div
                            class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl min-h-[800px] p-16 border border-gray-100 dark:border-gray-700">

                            @if (empty($blocks))
                                <div class="text-center py-32">
                                    <div
                                        class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 mb-6">
                                        <x-heroicon-o-document-plus
                                            class="w-10 h-10 text-primary-600 dark:text-primary-400" />
                                    </div>
                                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Commencez à créer
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg max-w-md mx-auto">
                                        Utilisez les boutons de la barre latérale pour ajouter du contenu
                                    </p>
                                </div>
                            @else
                                <div class="space-y-8">
                                    @foreach ($blocks as $blockIndex => $block)
                                        <div class="group relative" wire:key="block-{{ $block['id'] }}">

                                            {{-- BLOC TITRE --}}
                                            @if ($block['type'] === 'heading')
                                                <div
                                                    class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                    @if ($editingBlockId === $block['id'])
                                                        <div class="space-y-4">
                                                            <input type="text" wire:model="editingBlockData.text"
                                                                placeholder="Texte du titre"
                                                                class="w-full px-4 py-3 text-2xl font-bold border-2 border-primary-300 dark:border-primary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                                autofocus />
                                                            <select wire:model="editingBlockData.level"
                                                                class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                                <option value="h1">H1 - Titre principal</option>
                                                                <option value="h2">H2 - Titre de section</option>
                                                                <option value="h3">H3 - Sous-titre</option>
                                                                <option value="h4">H4 - Titre mineur</option>
                                                            </select>
                                                            <div class="flex gap-3">
                                                                <button type="button" wire:click="saveBlock"
                                                                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium shadow-md hover:shadow-lg transition-all duration-200">
                                                                    Enregistrer
                                                                </button>
                                                                <button type="button" wire:click="cancelEditing"
                                                                    class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                                                                    Annuler
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @php
                                                            $level = $block['data']['level'] ?? 'h2';
                                                            $text = $block['data']['text'] ?? '';
                                                            $sizeClass = match ($level) {
                                                                'h1' => 'text-4xl',
                                                                'h2' => 'text-3xl',
                                                                'h3' => 'text-2xl',
                                                                'h4' => 'text-xl',
                                                                default => 'text-2xl',
                                                            };
                                                        @endphp
                                                        <{{ $level }}
                                                            class="{{ $sizeClass }} font-bold text-gray-900 dark:text-gray-100 mb-2 cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                                                            wire:click="startEditing('{{ $block['id'] }}')">
                                                            {{ $text ?: 'Cliquez pour éditer le titre' }}
                                                            </{{ $level }}>
                                                            <div
                                                                class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                                <button type="button"
                                                                    wire:click="startEditing('{{ $block['id'] }}')"
                                                                    class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition-all"
                                                                    title="Éditer">
                                                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                                                </button>
                                                                <button type="button"
                                                                    wire:click="removeBlock('{{ $block['id'] }}')"
                                                                    class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                                    title="Supprimer">
                                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                                </button>
                                                            </div>
                                                    @endif
                                                </div>

                                                {{-- BLOC PARAGRAPHE --}}
                                            @elseif($block['type'] === 'paragraph')
                                                <div
                                                    class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                    @if ($editingBlockId === $block['id'])
                                                        <div class="space-y-4">
                                                            <textarea wire:model="editingBlockData.text" placeholder="Saisissez votre texte ici..." rows="6"
                                                                class="w-full px-4 py-3 border-2 border-primary-300 dark:border-primary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 resize-none"
                                                                autofocus></textarea>
                                                            <div class="flex gap-3">
                                                                <button type="button" wire:click="saveBlock"
                                                                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium shadow-md hover:shadow-lg transition-all duration-200">
                                                                    Enregistrer
                                                                </button>
                                                                <button type="button" wire:click="cancelEditing"
                                                                    class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                                                                    Annuler
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg cursor-pointer min-h-8 py-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                                                            wire:click="startEditing('{{ $block['id'] }}')">
                                                            {{ $block['data']['text'] ?: 'Cliquez pour ajouter du texte' }}
                                                        </p>
                                                        <div
                                                            class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                            <button type="button"
                                                                wire:click="startEditing('{{ $block['id'] }}')"
                                                                class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition-all"
                                                                title="Éditer">
                                                                <x-heroicon-o-pencil class="w-4 h-4" />
                                                            </button>
                                                            <button type="button"
                                                                wire:click="removeBlock('{{ $block['id'] }}')"
                                                                class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                                title="Supprimer">
                                                                <x-heroicon-o-trash class="w-4 h-4" />
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- BLOC TABLEAU --}}
                                            @elseif($block['type'] === 'table')
                                                <div
                                                    class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                    <div
                                                        class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                                                        <table
                                                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                            <thead
                                                                class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                                                <tr>
                                                                    @foreach ($block['data']['headers'] ?? ['Colonne 1', 'Colonne 2'] as $colIndex => $header)
                                                                        <th
                                                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider relative group">
                                                                            <input type="text"
                                                                                value="{{ $header }}"
                                                                                wire:blur="updateTableHeader('{{ $block['id'] }}', {{ $colIndex }}, $event.target.value)"
                                                                                class="w-full bg-transparent border-none focus:outline-none focus:ring-2 focus:ring-primary-500 rounded px-2 py-1 font-semibold"
                                                                                onclick="event.stopPropagation()" />
                                                                            <button
                                                                                wire:click="removeTableColumn('{{ $block['id'] }}', {{ $colIndex }})"
                                                                                class="absolute right-1 top-1 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 p-1 rounded transition-all"
                                                                                title="Supprimer la colonne"
                                                                                onclick="event.stopPropagation()">
                                                                                <x-heroicon-o-x-mark class="w-3 h-3" />
                                                                            </button>
                                                                        </th>
                                                                    @endforeach
                                                                    <th class="px-4 py-3">
                                                                        <button
                                                                            wire:click="addTableColumn('{{ $block['id'] }}')"
                                                                            class="w-full px-3 py-1.5 text-xs bg-primary-600 text-white rounded hover:bg-primary-700 transition-colors flex items-center justify-center gap-1"
                                                                            title="Ajouter une colonne">
                                                                            <x-heroicon-o-plus class="w-3 h-3" />
                                                                            Colonne
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                @foreach ($block['data']['rows'] ?? [['', ''], ['', '']] as $rowIndex => $row)
                                                                    <tr
                                                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                                        @foreach ($row as $colIndex => $cell)
                                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                                <input type="text"
                                                                                    value="{{ $cell }}"
                                                                                    wire:blur="updateTableCell('{{ $block['id'] }}', {{ $rowIndex }}, {{ $colIndex }}, $event.target.value)"
                                                                                    class="w-full px-2 py-1 text-sm text-gray-700 dark:text-gray-300 bg-transparent border-none focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
                                                                                    onclick="event.stopPropagation()" />
                                                                            </td>
                                                                        @endforeach
                                                                        <td></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Boutons de contrôle -->
                                                    <div class="mt-4 flex gap-2">
                                                        <button wire:click="addTableRow('{{ $block['id'] }}')"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition-all text-sm flex items-center gap-2">
                                                            <x-heroicon-o-plus class="w-4 h-4" />
                                                            Ajouter une ligne
                                                        </button>
                                                    </div>

                                                    <div
                                                        class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                        <button type="button"
                                                            wire:click="removeBlock('{{ $block['id'] }}')"
                                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Supprimer le tableau">
                                                            <x-heroicon-o-trash class="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- BLOC LISTE --}}
                                            @elseif($block['type'] === 'list')
                                                <div
                                                    class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                    <ul class="space-y-2">
                                                        @foreach ($block['data']['items'] ?? ['', ''] as $itemIndex => $item)
                                                            <li class="flex items-center gap-3 group/item">
                                                                <span
                                                                    class="text-primary-600 dark:text-primary-400 font-bold">•</span>
                                                                <input type="text" value="{{ $item }}"
                                                                    wire:blur="updateListItem('{{ $block['id'] }}', {{ $itemIndex }}, $event.target.value)"
                                                                    placeholder="Élément de liste"
                                                                    class="flex-1 px-2 py-1 text-gray-700 dark:text-gray-300 bg-transparent border-none focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
                                                                    onclick="event.stopPropagation()" />
                                                                <button
                                                                    wire:click="removeListItem('{{ $block['id'] }}', {{ $itemIndex }})"
                                                                    class="opacity-0 group-hover/item:opacity-100 text-red-500 hover:text-red-700 p-1 rounded transition-all"
                                                                    title="Supprimer l'élément"
                                                                    onclick="event.stopPropagation()">
                                                                    <x-heroicon-o-x-mark class="w-4 h-4" />
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                    <div class="mt-4 flex gap-2">
                                                        <button wire:click="addListItem('{{ $block['id'] }}')"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition-all text-sm flex items-center gap-2">
                                                            <x-heroicon-o-plus class="w-4 h-4" />
                                                            Ajouter un élément
                                                        </button>
                                                    </div>

                                                    <div
                                                        class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                        <button type="button"
                                                            wire:click="removeBlock('{{ $block['id'] }}')"
                                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Supprimer la liste">
                                                            <x-heroicon-o-trash class="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mini Sidebar - Outils -->
                <div
                    class="w-20 shrink-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-l border-gray-200/50 dark:border-gray-700/50 flex flex-col items-center py-6 shadow-lg">
                    <div class="space-y-4">
                        <button type="button" wire:click="addBlock('heading')"
                            class="w-14 h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800/30 dark:hover:to-blue-700/30 transition-all duration-200 group shadow-sm hover:shadow-md"
                            title="Ajouter un titre">
                            <x-heroicon-o-hashtag
                                class="w-6 h-6 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" />
                        </button>

                        <button type="button" wire:click="addBlock('paragraph')"
                            class="w-14 h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 hover:from-green-100 hover:to-green-200 dark:hover:from-green-800/30 dark:hover:to-green-700/30 transition-all duration-200 group shadow-sm hover:shadow-md"
                            title="Ajouter un paragraphe">
                            <x-heroicon-o-document-text
                                class="w-6 h-6 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" />
                        </button>

                        <button type="button" wire:click="addBlock('table')"
                            class="w-14 h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 hover:from-purple-100 hover:to-purple-200 dark:hover:from-purple-800/30 dark:hover:to-purple-700/30 transition-all duration-200 group shadow-sm hover:shadow-md"
                            title="Ajouter un tableau">
                            <x-heroicon-o-table-cells
                                class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" />
                        </button>

                        <button type="button" wire:click="addBlock('list')"
                            class="w-14 h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 hover:from-amber-100 hover:to-amber-200 dark:hover:from-amber-800/30 dark:hover:to-amber-700/30 transition-all duration-200 group shadow-sm hover:shadow-md"
                            title="Ajouter une liste">
                            <x-heroicon-o-list-bullet
                                class="w-6 h-6 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform" />
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
