<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Project;
use Filament\Widgets\Widget;

class RecentActivity extends Widget
{
    protected string $view = 'filament.widgets.recent-activity';

    protected int | string | array $columnSpan = 'full';

    public function getActivitiesProperty()
    {
        $leadQuery = Lead::with('user');
        if (auth()->user()->isSales()) {
            $leadQuery->where('user_id', auth()->id());
        }

        $leads = $leadQuery->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'Lead Baru',
                'description' => "Lead '{$item->name}' ditambahkan oleh {$item->user->name}",
                'date' => $item->created_at,
                'icon' => 'heroicon-o-user-plus',
                'color' => 'text-indigo-600 bg-indigo-50 border border-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
            ];
        });

        $projectQuery = Project::with(['user', 'lead']);
        if (auth()->user()->isSales()) {
            $projectQuery->where('user_id', auth()->id());
        }

        $projects = $projectQuery->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'Project ' . ucfirst(str_replace('_', ' ', $item->status)),
                'description' => "Project untuk '{$item->lead->name}' oleh {$item->user->name}",
                'date' => $item->created_at,
                'icon' => 'heroicon-o-briefcase',
                'color' => 'text-emerald-600 bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
            ];
        });

        return collect($leads)->merge($projects)->sortByDesc('date')->take(5);
    }
}
