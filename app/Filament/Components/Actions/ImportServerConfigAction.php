<?php

namespace App\Filament\Components\Actions;

use App\Exceptions\Service\InvalidFileUploadException;
use App\Services\Servers\Sharing\ServerConfigCreatorService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
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

        $this->label('Import');

        $this->iconButton();

        $this->icon('tabler-file-import');

        $this->iconSize(IconSize::ExtraLarge);

        $this->tooltip('Import server configuration from YAML file');

        $this->authorize(fn () => user()?->can('create server'));

        $this->modalHeading('Import Server Configuration');

        $this->modalDescription('Import server configuration from a YAML file to create a new server.');

        $this->schema([
            FileUpload::make('file')
                ->label('Configuration File')
                ->hint('Upload a YAML file exported from another panel')
                ->acceptedFileTypes(['application/x-yaml', 'text/yaml', 'text/x-yaml', '.yaml', '.yml'])
                ->preserveFilenames()
                ->previewable(false)
                ->storeFiles(false)
                ->required()
                ->maxSize(1024), // 1MB max
            Select::make('node_id')
                ->label('Node')
                ->hint('Select the node where the server will be created')
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
                    ->title('Server Created')
                    ->body("Server '{$server->name}' has been successfully created from configuration.")
                    ->success()
                    ->send();

                // Redirect to the new server's edit page
                redirect()->route('filament.admin.resources.servers.edit', ['record' => $server]);
            } catch (InvalidFileUploadException $exception) {
                Notification::make()
                    ->title('Import Failed')
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();
            } catch (\Exception $exception) {
                Notification::make()
                    ->title('Import Failed')
                    ->body('An unexpected error occurred: ' . $exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);
            }
        });
    }
}
