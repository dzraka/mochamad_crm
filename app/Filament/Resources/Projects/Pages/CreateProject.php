<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $project = $this->record;
        $totalPrice = $project->items->sum('subtotal');
        $needsApproval = $project->items->contains(function ($item) {
            return $item->negotiated_price < $item->normal_price;
        });
        $project->update([
            'total_price' => $totalPrice,
            'needs_approval' => $needsApproval,
        ]);
    }
}
