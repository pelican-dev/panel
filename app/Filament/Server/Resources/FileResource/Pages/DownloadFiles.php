<?php

namespace App\Filament\Server\Resources\FileResource\Pages;

use Filament\Panel;
use App\Models\Server;
use App\Facades\Activity;
use App\Models\Permission;
use Carbon\CarbonImmutable;
use Illuminate\Routing\Route;
use Filament\Facades\Filament;
use Livewire\Attributes\Locked;
use Filament\Resources\Pages\Page;
use App\Services\Nodes\NodeJWTService;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Server\Resources\FileResource;
use Illuminate\Support\Facades\Route as RouteFacade;

class DownloadFiles extends Page
{
    protected static string $resource = FileResource::class;

    #[Locked]
    public string $path;

    private NodeJWTService $nodeJWTService;

    public function boot(NodeJWTService $nodeJWTService): void
    {
        $this->nodeJWTService = $nodeJWTService;
    }

    public function mount(string $path): void
    {
        $this->authorizeAccess();

        /** @var Server $server */
        $server = Filament::getTenant();

        $token = $this->nodeJWTService
            ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
            ->setUser(auth()->user())
            ->setClaims([
                'file_path' => rawurldecode($path),
                'server_uuid' => $server->uuid,
            ])
            ->handle($server->node, auth()->user()->id . $server->uuid);

        Activity::event('server:file.download')
            ->property('file', $path)
            ->log();

        redirect()->away($server->node->getConnectionAddress() . '/download/file?token=' . $token->toString());
    }

    protected function authorizeAccess(): void
    {
        abort_unless(auth()->user()->can(Permission::ACTION_FILE_READ_CONTENT, Filament::getTenant()), 403);
    }

    public static function route(string $path): PageRegistration
    {
        return new PageRegistration(
            page: static::class,
            route: fn (Panel $panel): Route => RouteFacade::get($path, static::class)
                ->middleware(static::getRouteMiddleware($panel))
                ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
                ->where('path', '.*'),
        );
    }
}
