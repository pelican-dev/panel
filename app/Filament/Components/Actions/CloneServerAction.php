<?php

namespace App\Filament\Components\Actions;

use App\Enums\TablerIcon;
use App\Filament\Admin\Resources\Servers\Pages\CreateServer;
use App\Models\Server;
use Filament\Actions\Action;

class CloneServerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'clone';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('admin/server.clone'));

        $this->tooltip(trans('admin/server.clone'));

        $this->icon(TablerIcon::Copy);

        $this->url(fn (Server $server) => CreateServer::getUrl(['clone_from' => $server->id], panel: 'admin'));

        $this->authorize(fn (Server $server) => user()?->can('view', $server) && user()->can('create', Server::class));
    }
}
