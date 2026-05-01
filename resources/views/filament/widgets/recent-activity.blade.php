<x-filament-widgets::widget>
    <x-filament::section compact class="bg-white dark:bg-black border-0 shadow-sm rounded-lg ring-1 ring-gray-900/5 dark:ring-white/10">
        <x-slot name="heading">
            <span class="text-gray-900 dark:text-white font-semibold">Aktivitas Terbaru</span>
        </x-slot>
        <div class="space-y-4 mt-2">
            @forelse($this->activities as $activity)
                <div class="flex items-center gap-4">
                    <div class="p-2 rounded-full {{ $activity['color'] }}">
                        <x-filament::icon :icon="$activity['icon']" class="w-5 h-5" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['type'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['description'] }}</p>
                    </div>
                    <div class="text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">
                        {{ $activity['date']->diffForHumans() }}
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
