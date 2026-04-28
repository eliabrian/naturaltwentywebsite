<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class SalesReport extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Sales Report';
    protected static ?string $title = 'Sales Report';
    protected string $view = 'filament.pages.sales-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select(
                        DB::raw('MIN(orders.id) as id'),
                        DB::raw('DATE(orders.created_at) as order_date'),
                        DB::raw('COUNT(DISTINCT orders.id) as total_transactions'),
                        DB::raw('SUM(order_items.quantity) as products_sold'),
                        DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
                    )
                    ->where('orders.status', '!=', 'awaiting_payment')
                    ->groupBy(DB::raw('DATE(orders.created_at)'))
            )
            ->columns([
                TextColumn::make('order_date')
                    ->label('Date')
                    ->date('l, d F Y')
                    ->sortable(),
                    
                TextColumn::make('total_transactions')
                    ->label('Total Transaction')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('products_sold')
                    ->label('Product Sold')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('total_sales')
                    ->label('Total Sales')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('details')
                    ->icon(Heroicon::OutlinedPlusCircle)
                    ->label('Product Details')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Sales Report: ' . \Carbon\Carbon::parse($record->order_date)->format('d F Y'))
                    ->modalContent(function ($record) {
                        
                        $items = OrderItem::query()
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                            ->whereDate('orders.created_at', $record->order_date)
                            ->select(
                                'menu_items.name',
                                DB::raw('SUM(order_items.quantity) as total_qty'),
                                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
                            )
                            ->groupBy('menu_items.id', 'menu_items.name')
                            ->orderByDesc('total_qty')
                            ->get();

                        return view('filament.pages.sales-report-details', ['items' => $items]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ]);
    }
}
