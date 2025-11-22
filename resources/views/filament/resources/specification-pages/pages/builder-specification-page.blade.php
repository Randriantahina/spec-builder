<x-filament-panels::page>
    <div
        class="flex h-[calc(100vh-8rem)] gap-0 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Mini Sidebar - Modern Design -->
        <div
            class="w-20 shrink-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-r border-gray-200/50 dark:border-gray-700/50 flex flex-col items-center py-6 shadow-sm">
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

                <button type="button" wire:click="addBlock('section')"
                    class="w-14 h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 hover:from-amber-100 hover:to-amber-200 dark:hover:from-amber-800/30 dark:hover:to-amber-700/30 transition-all duration-200 group shadow-sm hover:shadow-md"
                    title="Ajouter une section">
                    <x-heroicon-o-square-3-stack-3d
                        class="w-6 h-6 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform" />
                </button>
            </div>
        </div>

        <!-- Main Editor Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Editor Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="max-w-4xl mx-auto px-8 py-16">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl min-h-[800px] p-16 border border-gray-100 dark:border-gray-700">
                        @if (empty($content))
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
                                    Utilisez les boutons de la barre latérale pour ajouter du contenu à votre
                                    spécification
                                </p>
                            </div>
                        @else
                            <div class="space-y-8">
                                @foreach ($content as $index => $block)
                                    <div class="group relative" wire:key="block-{{ $index }}">
                                        <!-- Block Content -->
                                        @if ($block['type'] === 'heading')
                                            <div
                                                class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                @if ($editingBlockIndex === $index)
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
                                                        wire:click="startEditing({{ $index }})">
                                                        {{ $text ?: 'Cliquez pour éditer le titre' }}
                                                        </{{ $level }}>
                                                        <div
                                                            class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                            <button type="button"
                                                                wire:click="startEditing({{ $index }})"
                                                                class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition-all"
                                                                title="Éditer">
                                                                <x-heroicon-o-pencil class="w-4 h-4" />
                                                            </button>
                                                            <button type="button"
                                                                wire:click="removeBlock({{ $index }})"
                                                                class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                                title="Supprimer">
                                                                <x-heroicon-o-trash class="w-4 h-4" />
                                                            </button>
                                                        </div>
                                                @endif
                                            </div>
                                        @elseif($block['type'] === 'paragraph')
                                            <div
                                                class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                @if ($editingBlockIndex === $index)
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
                                                        wire:click="startEditing({{ $index }})">
                                                        {{ $block['data']['text'] ?: 'Cliquez pour ajouter du texte' }}
                                                    </p>
                                                    <div
                                                        class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                        <button type="button"
                                                            wire:click="startEditing({{ $index }})"
                                                            class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Éditer">
                                                            <x-heroicon-o-pencil class="w-4 h-4" />
                                                        </button>
                                                        <button type="button"
                                                            wire:click="removeBlock({{ $index }})"
                                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Supprimer">
                                                            <x-heroicon-o-trash class="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($block['type'] === 'table')
                                            <div
                                                class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                @if ($editingBlockIndex === $index)
                                                    <div class="space-y-4">
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">Tableau
                                                            créé. L'édition avancée sera disponible prochainement.</p>
                                                        <div class="flex gap-3">
                                                            <button type="button" wire:click="cancelEditing"
                                                                class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors">
                                                                Fermer
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                                                        <table
                                                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                            <thead
                                                                class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                                                <tr>
                                                                    @foreach ($block['data']['headers'] ?? ['', ''] as $header)
                                                                        <th
                                                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                                            {{ $header ?: 'En-tête' }}
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                @foreach ($block['data']['rows'] ?? [['', '']] as $row)
                                                                    <tr
                                                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                                        @foreach ($row as $cell)
                                                                            <td
                                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                                                {{ $cell ?: 'Cellule' }}
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div
                                                        class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                        <button type="button"
                                                            wire:click="startEditing({{ $index }})"
                                                            class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Éditer">
                                                            <x-heroicon-o-pencil class="w-4 h-4" />
                                                        </button>
                                                        <button type="button"
                                                            wire:click="removeBlock({{ $index }})"
                                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Supprimer">
                                                            <x-heroicon-o-trash class="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($block['type'] === 'section')
                                            <div
                                                class="relative p-6 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200 border-l-4 border-primary-500 bg-gradient-to-r from-primary-50/50 to-transparent dark:from-primary-900/10 dark:to-transparent">
                                                @if ($editingBlockIndex === $index)
                                                    <div class="space-y-4">
                                                        <input type="text" wire:model="editingBlockData.title"
                                                            placeholder="Titre de la section"
                                                            class="w-full px-4 py-3 text-xl font-semibold border-2 border-primary-300 dark:border-primary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                            autofocus />
                                                        <textarea wire:model="editingBlockData.description" placeholder="Description de la section" rows="4"
                                                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 resize-none"></textarea>
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
                                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3 cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                                                        wire:click="startEditing({{ $index }})">
                                                        {{ $block['data']['title'] ?: 'Cliquez pour ajouter un titre de section' }}
                                                    </h3>
                                                    <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed cursor-pointer hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                                                        wire:click="startEditing({{ $index }})">
                                                        {{ $block['data']['description'] ?: 'Cliquez pour ajouter une description' }}
                                                    </p>
                                                    <div
                                                        class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                                        <button type="button"
                                                            wire:click="startEditing({{ $index }})"
                                                            class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Éditer">
                                                            <x-heroicon-o-pencil class="w-4 h-4" />
                                                        </button>
                                                        <button type="button"
                                                            wire:click="removeBlock({{ $index }})"
                                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg transition-all"
                                                            title="Supprimer">
                                                            <x-heroicon-o-trash class="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
