<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lead_id')
                    ->relationship('lead', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('approved_by')
                    ->numeric(),
                Select::make('status')
                    ->options(['waiting_approval' => 'Waiting approval', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                    ->default('waiting_approval')
                    ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                Toggle::make('needs_approval')
                    ->required(),
                DateTimePicker::make('approved_at'),
            ]);
    }
}
