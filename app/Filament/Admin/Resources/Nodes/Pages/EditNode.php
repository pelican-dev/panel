<?php

namespace App\Filament\Admin\Resources\Nodes\Pages;

use App\Enums\TablerIcon;
use App\Filament\Admin\Resources\Nodes\NodeResource;
use App\Models\Node;
use App\Repositories\Daemon\DaemonSystemRepository;
use App\Services\Nodes\NodeUpdateService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use App\Traits\Filament\CanCustomizeTabs;
use App\Traits\Filament\NodeDetailTabs;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Http\Client\ConnectionException;

class EditNode extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;
    use CanCustomizeTabs;
    use NodeDetailTabs;

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
        return $schema->components([
            Tabs::make('Tabs')
                ->columns([
                    'default' => 2,
                    'sm' => 3,
                    'md' => 3,
                    'lg' => 4,
                ])
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs($this->getTabs()),
        ]);
    }

    /** @return Tab[] */
    protected function getDefaultTabs(): array
    {
        return $this->detailTabs();
    }

    protected function getFormActions(): array
    {
        return [];
    }

    /** @return array<Action|Actions> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Node $node) => $node->servers()->count() > 0)
                ->tooltip(fn (Node $node) => $node->servers()->count() > 0 ? trans('admin/node.node_has_servers') : trans('filament-actions::delete.single.label')),
            Action::make('save')
                ->hiddenLabel()
                ->action('save')
                ->keyBindings(['mod+s'])
                ->tooltip(trans('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->icon(TablerIcon::DeviceFloppy),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!$data['behind_proxy']) {
            $data['daemon_listen'] = $data['daemon_connect'];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->fillForm();

        /** @var Node $node */
        $node = $this->record;

        $changed = collect($node->getChanges())->except(['updated_at', 'name', 'tags', 'public', 'maintenance_mode', 'memory', 'memory_overallocate', 'disk', 'disk_overallocate', 'cpu', 'cpu_overallocate'])->all();

        try {
            if ($changed) {
                $this->daemonSystemRepository->setNode($node)->update($node);
            }
            parent::getSavedNotification()?->send();
        } catch (ConnectionException) {
            Notification::make()
                ->title(trans('admin/node.error_connecting', ['node' => $node->name]))
                ->body(trans('admin/node.error_connecting_description'))
                ->color('warning')
                ->icon(TablerIcon::Database)
                ->warning()
                ->send();
        }
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function getColumnSpan(): ?int
    {
        return null;
    }

    protected function getColumnStart(): ?int
    {
        return null;
    }
}
