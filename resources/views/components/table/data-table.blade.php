@props([
    'headers' => [],
    'rows' => [],
    'emptyMessage' => 'No data found',
    'emptyIcon' => 'article',
    'emptyDescription' => 'Get started by adding some data.',
    'tableId' => 'default-table'
])

<div class="bg-white rounded-xl border border-slate-200 p-1">
    <div class="overflow-x-auto">
        <table id="{{ $tableId }}" class="min-w-full divide-y divide-slate-200">
            <thead>
                <tr class="bg-slate-50">
                    @foreach($headers as $header)
                    <th class="px-6 py-4 text-left">
                        <span class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                            @if(isset($header['icon']))
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg {{ $header['iconBg'] ?? 'bg-slate-50' }} {{ $header['iconColor'] ?? 'text-slate-600' }} mr-2">
                                <i class="material-symbols-outlined text-base">{{ $header['icon'] }}</i>
                            </span>
                            @endif
                            {{ $header['title'] }}
                        </span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($rows as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                        @foreach($row as $cell)
                            <td class="px-6 py-4">
                                {!! $cell !!}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <i class="material-symbols-outlined text-2xl text-slate-400">{{ $emptyIcon }}</i>
                                </div>
                                <h3 class="text-sm font-medium text-slate-900 mb-1">{{ $emptyMessage }}</h3>
                                <p class="text-sm text-slate-500">{{ $emptyDescription }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@once
@push('scripts')
<script>
$(document).ready(function() {
    const dataTable = new simpleDatatables.DataTable("#{{ $tableId }}", {
        searchable: true,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25],
        labels: {
            placeholder: "Search...",
            perPage: "Items per page",
            noRows: "{{ $emptyMessage }}",
            info: "Showing {start} to {end} of {rows} items",
        },
    });
});
</script>
@endpush
@endonce 