<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Project;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ReportPage extends Page
{
    protected string $view = 'filament.pages.report-page';

    protected static ?string $title = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    public ?string $start_date = null;
    public ?string $end_date = null;

    public function mount(): void
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function getStats(): array
    {
        $query = fn($model) => $model::query()
            ->whereBetween('created_at', [$this->start_date, $this->end_date . ' 23:59:59']);
        if (auth()->user()->isSales()) {
            $query = fn($model) => $model::query()
                ->where('user_id', auth()->id())
                ->whereBetween('created_at', [$this->start_date, $this->end_date . ' 23:59:59']);
        }
        return [
            'total_leads' => $query(Lead::class)->count(),
            'leads_converted' => $query(Lead::class)->where('status', 'converted')->count(),
            'total_projects' => $query(Project::class)->count(),
            'projects_approved' => $query(Project::class)->where('status', 'approved')->count(),
            'projects_rejected' => $query(Project::class)->where('status', 'rejected')->count(),
            'total_customers' => $query(Customer::class)->count(),
            'total_revenue' => $query(Project::class)->where('status', 'approved')->sum('total_price'),
        ];
    }

    public function getProjects(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Project::with(['lead', 'user'])
            ->whereBetween('created_at', [$this->start_date, $this->end_date . ' 23:59:59'])
            ->orderBy('created_at', 'desc');
        if (auth()->user()->isSales()) {
            $query->where('user_id', auth()->id());
        }
        return $query->get();
    }

    public function filter(): void {}
}
