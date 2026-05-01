<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfessionalStatsOverview extends BaseWidget
{
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getStats(): array
    {
        $start = $this->startDate ?? now()->startOfMonth()->format('Y-m-d');
        $end = $this->endDate ? $this->endDate . ' 23:59:59' : now()->endOfMonth()->format('Y-m-d 23:59:59');

        $queryRevenue = Project::where('status', 'approved')
            ->whereBetween('created_at', [$start, $end]);

        $queryCustomers = Customer::whereBetween('created_at', [$start, $end]);
        $queryLeads = Lead::whereBetween('created_at', [$start, $end]);

        if (auth()->user()->isSales()) {
            $queryRevenue->where('user_id', auth()->id());
            $queryCustomers->where('user_id', auth()->id());
            $queryLeads->where('user_id', auth()->id());
        }

        $revenue = $queryRevenue->sum('total_price');
        $customers = $queryCustomers->count();
        $leads = $queryLeads->count();

        $cleanClass = 'rounded-lg bg-white dark:bg-black border-0 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10';

        return [
            Stat::make('Total Revenue', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->extraAttributes(['class' => $cleanClass]),
            Stat::make('Total Customer', $customers)
                ->extraAttributes(['class' => $cleanClass]),
            Stat::make('Total Lead', $leads)
                ->extraAttributes(['class' => $cleanClass]),
        ];
    }
}
