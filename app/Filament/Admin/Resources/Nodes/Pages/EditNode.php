<?php

namespace App\Filament\Admin\Resources\Nodes\Pages;

use App\Filament\Admin\Resources\Nodes\NodeResource;
use App\Models\Node;
use App\Repositories\Daemon\DaemonSystemRepository;
use App\Services\Nodes\NodeUpdateService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Illuminate\Http\Client\ConnectionException;

class EditNode extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = NodeResource::class;

    private DaemonSystemRepository $daemonSystemRepository;
    private NodeUpdateService $nodeUpdateService;

    public function boot(DaemonSystemRepository $daemonSystemRepository, NodeUpdateService $nodeUpdateService): void
    {
        $this->daemonSystemRepository = $daemonSystemRepository;
        $this->nodeUpdateService = $nodeUpdateService;
    }

    public function form(Schema $schema): Schema
    {
        return NodeResource::schema($schema);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $node = Node::findOrFail($data['id']);

        if (!is_ip($node->fqdn)) {
            $ip = get_ip_from_hostname($node->fqdn);
            if ($ip) {
                $data['dns'] = true;
                $data['ip'] = $ip;
            } else {
                $data['dns'] = false;
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!$data['behind_proxy']) {
            $data['daemon_listen'] = $data['daemon_connect'];
        }

        return $data;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    /** @return array<Action> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Node $node) => $node->servers()->count() > 0)
                ->label(fn (Node $node) => $node->servers()->count() > 0 ? trans('admin/node.node_has_servers') : trans('filament-actions::delete.single.label'))
                ->iconButton()->iconSize(IconSize::ExtraLarge),
            Action::make('save')
                ->label(trans('filament-actions::edit.single.label'))
                ->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy')
                ->action('save'),
        ];
    }

    protected function afterSave(): void
    {
        $this->refresh();

        /** @var Node $node */
        $node = $this->getRecord();

        $changed = collect($node->getChanges())->except(['updated_at', 'name', 'tags', 'public', 'maintenance_mode', 'memory', 'memory_overallocate', 'disk', 'disk_overallocate', 'cpu', 'cpu_overallocate'])->all();

        try {
            if ($changed) {
                $this->daemonSystemRepository->setNode($node)->update($node);
            }
            $this->getSavedNotification()?->send();
        } catch (ConnectionException) {
            Notification::make()
                ->title(trans('admin/node.error_connecting', ['node' => $node->name]))
                ->body(trans('admin/node.error_connecting_description'))
                ->color('warning')
                ->icon('tabler-database')
                ->warning()
                ->send();
        }
    }
    protected function getSavedNotification(): ?Notification
    {
        return null;
    }
    protected function getColumnSpan() {
        return null;
    }

  protected function getColumnStart(): ?int
    {
        return null;
    }
}
