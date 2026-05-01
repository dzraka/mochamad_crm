<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Project';

    protected static ?string $modelLabel = 'Project';

    protected static ?string $pluralModelLabel = 'Daftar Project';

    protected static ?int $navigationSort = 3;

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('lead_id')
                    ->label('Lead')
                    ->relationship(
                        'lead',
                        'name',
                        fn($query) => auth()->user()->isSales()
                            ? $query->where('user_id', auth()->id())
                            : $query
                    )
                    ->noOptionsMessage('Tidak ada data lead')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->validationMessages([
                        'required' => 'Lead wajib diisi'
                    ]),
                Repeater::make('items')
                    ->label('Produk')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->noOptionsMessage('Tidak ada data produk')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $product = \App\Models\Product::find($state);
                                    $set('normal_price', $product?->selling_price ?? 0);
                                    $set('negotiated_price', $product?->selling_price ?? 0);
                                }
                            }),
                        TextInput::make('normal_price')
                            ->label('Harga Normal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('negotiated_price')
                            ->label('Harga Negosiasi')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('qty')
                            ->label('Qty')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1),
                    ])
                    ->columns(4)
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.name')
                    ->label('Lead')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Sales'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'waiting_approval' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn($record) =>
                        auth()->user()->isManager() &&
                            $record->status === 'waiting_approval'
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Approve Project')
                    ->modalDescription('Apakah anda yakin ingin approve project ini ?')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                        $lead = $record->lead;
                        $customer = \App\Models\Customer::create([
                            'lead_id' => $lead->id,
                            'project_id' => $record->id,
                            'user_id' => $record->user_id,
                            'name' => $lead->name,
                            'contact' => $lead->contact,
                            'address' => $lead->address,
                        ]);
                        foreach ($record->items as $item) {
                            \App\Models\CustomerService::create([
                                'customer_id' => $customer->id,
                                'product_id' => $item->product_id,
                                'subscription_price' => $item->negotiated_price,
                                'start_date' => now(),
                            ]);
                        }
                        $lead->update(['status' => 'converted']);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(
                        fn($record) =>
                        auth()->user()->isManager()
                            && $record->status === 'waiting_approval'
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Project')
                    ->modalDescription('Apakah anda yakin ingin menolak project ini?')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                        $record->lead->update(['status' => 'rejected']);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->isSales()) {
            $query->where('user_id', auth()->id());
        }
        return $query;
    }
}
