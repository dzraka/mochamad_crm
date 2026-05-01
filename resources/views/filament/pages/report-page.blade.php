<x-filament-panels::page>
    <div class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
            <input type="date" wire:model="start_date"
                class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
            <input type="date" wire:model="end_date"
                class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        </div>
        <button wire:click="filter"
            class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Filter
        </button>
    </div>
    @php $stats = $this->getStats(); @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Lead</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_leads'] }}</p>
            <p class="text-xs text-green-600">{{ $stats['leads_converted'] }} converted</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Project</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_projects'] }}</p>
            <p class="text-xs text-green-600">{{ $stats['projects_approved'] }} approved</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Customer</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_customers'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Project</h3>
            <button wire:click="export"
                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                Export Excel
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">Lead</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total Harga</th>
                        <th class="px-4 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse ($this->getProjects() as $project)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $project->lead->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $project->user->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'waiting_approval' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$project->status] ?? '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                                Rp {{ number_format($project->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                {{ $project->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data project</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
