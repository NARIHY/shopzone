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
            <div class="flex-1 flex items-center gap-2">
                {{-- Recherche globale --}}
                <input
                    id="tableFilter"
                    type="text"
                    placeholder="Rechercher..."
                    class="w-full md:w-64 border rounded px-3 py-1.5 text-sm"
                />

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
    <table class="w-full data-table">
        <thead class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <tr>
                @foreach($columns as $col)
                    <th
                        data-col="{{ $loop->index }}"
                        data-sort="none"
                        class="px-6 py-3 text-{{ $col['align'] ?? 'left' }}
                               text-xs font-medium uppercase tracking-wider
                               text-gray-500 dark:text-gray-400
                               cursor-pointer select-none">

                        <div class="flex items-center gap-1">
                            <span>{{ $col['label'] }}</span>
                            <span class="sort-indicator text-[10px]"></span>
                        </div>

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

<script>
document.addEventListener('DOMContentLoaded', () => {

    const table = document.querySelector('.data-table');
    if(!table) return;

    const tbody = table.querySelector('tbody');
    let rows = Array.from(tbody.querySelectorAll('tr'));
    const filterInput = document.getElementById('tableFilter');

    // ======================
    // 🔎 FILTRE GLOBAL
    // ======================
    if(filterInput){
        filterInput.addEventListener('input', () => {
            const value = filterInput.value.toLowerCase();

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value)
                    ? ''
                    : 'none';
            });
        });
    }

    // ======================
    // 🔃 TRI PAR COLONNE
    // ======================
    table.querySelectorAll('th[data-col]').forEach(th => {

        th.addEventListener('click', () => {

            const colIndex = parseInt(th.dataset.col);
            let sortDirection = th.dataset.sort === 'asc' ? 'desc' : 'asc';

            // reset autres colonnes
            table.querySelectorAll('th').forEach(h => {
                if(h !== th){
                    h.dataset.sort = 'none';
                    const icon = h.querySelector('.sort-indicator');
                    if(icon) icon.textContent = '';
                }
            });

            th.dataset.sort = sortDirection;

            const indicator = th.querySelector('.sort-indicator');
            if(indicator){
                indicator.textContent = sortDirection === 'asc' ? '▲' : '▼';
            }

            rows.sort((a,b) => {

                let aText = a.children[colIndex]?.innerText.trim().toLowerCase() || '';
                let bText = b.children[colIndex]?.innerText.trim().toLowerCase() || '';

                // tri numérique si possible
                let aNum = parseFloat(aText.replace(',', '.'));
                let bNum = parseFloat(bText.replace(',', '.'));

                if(!isNaN(aNum) && !isNaN(bNum)){
                    return sortDirection === 'asc'
                        ? aNum - bNum
                        : bNum - aNum;
                }

                return sortDirection === 'asc'
                    ? aText.localeCompare(bText)
                    : bText.localeCompare(aText);
            });

            // réinjecter lignes triées
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        });

    });

});
</script>
