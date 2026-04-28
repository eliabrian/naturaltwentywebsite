<?php

namespace App\Filament\Resources\CafeTables\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CafeTablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Table Name/Number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('print_qr')
                    ->label('Show QR Code')
                    ->icon(Heroicon::OutlinedQrCode)
                    ->color('success')
                    ->modalHeading(fn ($record) => 'Table ' . $record->name . ' QR Code')
                    ->modalContent(function ($record) {
                        $orderingUrl = url('/order/' . $record->token);
                        $qrCode = QrCode::size(250)
                        ->margin(1)
                        ->generate($orderingUrl);

                        return new HtmlString('
                            <div style="text-align: center; padding: 2rem;">
                                <div style="display: inline-block; padding: 1rem; background: white; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                    ' . $qrCode . '
                                </div>
                                <p style="margin-top: 1rem; font-weight: bold; font-size: 1.25rem;">Table ' . $record->name . '</p>
                                <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; word-break: break-all;">
                                    ' . $orderingUrl . '
                                </p>
                                <button onclick="window.print()" style="margin-top: 1.5rem; padding: 0.5rem 1rem; background: #3b82f6; color: white; border-radius: 0.5rem; font-weight: bold;">
                                    Print Sticker
                                </button>
                            </div>
                        ');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
