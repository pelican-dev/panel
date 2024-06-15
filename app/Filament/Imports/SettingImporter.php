<?php

namespace App\Filament\Imports;

use App\Models\Setting;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SettingImporter extends Importer
{
    protected static ?string $model = Setting::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('key'),
            ImportColumn::make('label'),
            ImportColumn::make('value'),
            ImportColumn::make('type'),
            ImportColumn::make('tabs'),
            ImportColumn::make('description'),
        ];
    }

    public function resolveRecord(): ?Setting
    {
        // return Setting::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Setting();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your setting import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
