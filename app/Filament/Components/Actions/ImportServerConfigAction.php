<?php

namespace App\Filament\Components\Actions;

use App\Exceptions\Service\InvalidFileUploadException;
use App\Services\Servers\Sharing\ServerConfigCreatorService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;

class ImportServerConfigAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'import_config';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();

        $this->icon('tabler-file-import');

        $this->tooltip(trans('admin/server.import_export.import_tooltip'));

        $this->authorize(fn () => user()?->can('create server'));

        $this->modalHeading(trans('admin/server.import_export.import_heading'));

        $this->modalDescription(trans('admin/server.import_export.import_description'));

        $this->schema([
            FileUpload::make('file')
                ->label(trans('admin/server.import_export.config_file'))
                ->hint(trans('admin/server.import_export.config_file_hint'))
                ->acceptedFileTypes(['application/x-yaml', 'text/yaml', 'text/x-yaml', '.yaml', '.yml'])
                ->preserveFilenames()
                ->previewable(false)
                ->storeFiles(false)
                ->required()
                ->maxSize(1024), // 1MB max
            Select::make('node_id')
                ->label(trans('admin/server.import_export.node_select'))
                ->hint(trans('admin/server.import_export.node_select_hint'))
                ->options(fn () => user()?->accessibleNodes()->pluck('name', 'id') ?? [])
                ->searchable()
                ->required()
                ->visible(fn () => (user()?->accessibleNodes()->count() ?? 0) > 1)
                ->default(fn () => user()?->accessibleNodes()->first()?->id),
        ]);

        $this->action(function (ServerConfigCreatorService $createService, array $data): void {
            /** @var UploadedFile $file */
            $file = $data['file'];
            $nodeId = $data['node_id'] ?? null;

            try {
                $server = $createService->fromFile($file, $nodeId);

                Notification::make()
                    ->title(trans('admin/server.notifications.import_created'))
                    ->body(trans('admin/server.notifications.import_created_body', ['name' => $server->name]))
                    ->success()
                    ->send();

                redirect()->route('filament.admin.resources.servers.edit', ['record' => $server]);
            } catch (InvalidFileUploadException $exception) {
                Notification::make()
                    ->title(trans('admin/server.notifications.import_failed'))
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();
            } catch (\Exception $exception) {
                Notification::make()
                    ->title(trans('admin/server.notifications.import_failed'))
                    ->body(trans('admin/server.notifications.import_failed_body', ['error' => $exception->getMessage()]))
                    ->danger()
                    ->send();

                report($exception);
            }
        });
    }
}
