<?php

namespace App\Livewire;

use App\Enums\TablerIcon;
use App\Models\Node;
use App\Services\Nodes\NodeJWTService;
use App\Services\Servers\GetUserPermissionsService;
use Filament\Support\Enums\IconSize;
use Filament\Tables\View\Components\Columns\IconColumnComponent\IconComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Attributes\Locked;
use Livewire\Component;

use function Filament\Support\generate_icon_html;

class NodeClientConnectivity extends Component
{
    #[Locked]
    public Node $node;

    private GetUserPermissionsService $getUserPermissionsService;

    private NodeJWTService $nodeJWTService;

    public function boot(GetUserPermissionsService $getUserPermissionsService, NodeJWTService $nodeJWTService): void
    {
        $this->getUserPermissionsService = $getUserPermissionsService;
        $this->nodeJWTService = $nodeJWTService;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $httpUrl = $this->node->getConnectionAddress();

        $wsUrl = null;
        $wsToken = null;

        $server = $this->node->servers()->first();

        if ($server) {
            $user = Auth::user();

            $permissions = $this->getUserPermissionsService->handle($server, $user);

            $wsToken = $this->nodeJWTService
                ->setExpiresAt(now()->addMinute()->toImmutable())
                ->setUser($user)
                ->setClaims([
                    'server_uuid' => $server->uuid,
                    'permissions' => $permissions,
                ])
                ->handle($this->node, $user->id . $server->uuid)->toString();

            $wsUrl = str_replace(['https://', 'http://'], ['wss://', 'ws://'], $this->node->getConnectionAddress());
            $wsUrl .= sprintf('/api/servers/%s/ws', $server->uuid);
        }

        return view('livewire.node-client-connectivity', [
            'httpUrl' => $httpUrl,
            'wsUrl' => $wsUrl,
            'wsToken' => $wsToken,
            'loadingIcon' => $this->makeIcon(TablerIcon::WorldQuestion, 'warning', 'Checking...'),
            'offlineIcon' => $this->makeIcon(TablerIcon::WorldX, 'danger', 'Node is not reachable from your browser'),
            'onlineIcon' => $this->makeIcon(TablerIcon::WorldCheck, 'success', 'Node is reachable'),
            'warningIcon' => $this->makeIcon(TablerIcon::WorldExclamation, 'warning', 'Node is reachable, but WebSocket failed. Check reverse proxy config.'),
            'onlineNoWsIcon' => $this->makeIcon(TablerIcon::WorldCheck, 'success', 'Node is reachable (WebSocket not tested — no servers)'),
        ]);
    }

    private function makeIcon(TablerIcon $icon, string $color, string $tooltip): string
    {
        return generate_icon_html($icon, attributes: (new ComponentAttributeBag())
            ->merge([
                'x-tooltip' => '{
                    content: "' . $tooltip . '",
                    theme: $store.theme,
                    allowHTML: true,
                    placement: "bottom",
                }',
                'style' => 'color: var(--dark-text, var(--text))',
            ], escape: false)
            ->color(IconComponent::class, $color), size: IconSize::Large)
            ->toHtml();
    }

    public function placeholder(): string
    {
        return generate_icon_html(TablerIcon::WorldQuestion, attributes: (new ComponentAttributeBag())
            ->color(IconComponent::class, 'warning'), size: IconSize::Large)
            ->toHtml();
    }
}
