@props([
    'notice' => null,
    'columns' => [],
])

<div {{ $attributes->class([
        'rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-900'
    ]) }}>
    {{-- Notice facultative --}}
    @if($notice)
        <div class="border-b border-yellow-200 dark:border-yellow-700 px-4 py-3">
            <p class="text-sm text-yellow-800 dark:text-yellow-200 text-center">
                {{ $notice }}
            </p>
        </div>
    @endif

    {{-- Toolbar slot (search, boutons, filtres...) --}}
    @if (isset($toolbar) || isset($actions))
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1">
                    {{ $toolbar ?? '' }}
                </div>
                <div class="flex items-center gap-2">
                    {{ $actions ?? '' }}
                </div>
            </div>
        </div>
    @endif

    {{-- Tableau --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <tr>
                    @foreach($columns as $col)
                        <th class="px-6 py-3 text-{{ $col['align'] ?? 'left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ $col['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    {{-- Pagination slot --}}
    @isset($pagination)
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $pagination }}
        </div>
    @endisset
</div>
