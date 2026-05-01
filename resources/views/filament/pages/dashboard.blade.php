<x-filament-panels::page>
    {{-- Header --}}  
    <div class="flex flex-wrap items-end gap-4 mb-2 p-5 bg-white dark:bg-black rounded-lg shadow-sm border-0 ring-1 ring-gray-900/5 dark:ring-white/10">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
            <input type="date" wire:model.live="start_date"
                class="border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg px-4 py-2 text-sm text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="end_date"
                class="border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg px-4 py-2 text-sm text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
        </div>
        <button wire:click="$refresh"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm border border-transparent">
            Filter Data
        </button>
    </div>

    {{-- Stats Widget --}}
    @livewire(\App\Filament\Widgets\ProfessionalStatsOverview::class, ['startDate' => $start_date, 'endDate' => $end_date], key($start_date . $end_date))

    {{-- Chart --}}
    <div class="mt-6">
        @livewire(\App\Filament\Widgets\RevenueChart::class)
    </div>

    {{-- Recent Activity --}}
    <div class="mt-6">
        @livewire(\App\Filament\Widgets\RecentActivity::class)
    </div>

    {{-- Project --}}
    <div class="mt-6 bg-white dark:bg-black rounded-lg shadow-sm border-0 ring-1 ring-gray-900/5 dark:ring-white/10 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Detail Project</h3>
            <button wire:click="export"
                class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm border border-transparent">
                Export Excel
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3 font-medium">Lead</th>
                        <th class="px-5 py-3 font-medium">Sales</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Total Harga</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($this->getProjects() as $project)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-5 py-4 text-gray-900 dark:text-gray-100">{{ $project->lead->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $project->user->name ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $colors = [
                                        'waiting_approval' => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                        'approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                                        'rejected' => 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $colors[$project->status] ?? '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right text-gray-900 dark:text-gray-100 font-medium">
                                Rp {{ number_format($project->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                                {{ $project->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data project</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
