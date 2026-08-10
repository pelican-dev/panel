<?php

namespace App\Http\Middleware;

use App\Helpers\PasskeyOrigin;
use Closure;
use Illuminate\Http\Request;
use Laravel\Passkeys\Passkeys;
use Symfony\Component\HttpFoundation\Response;

class AllowPasskeyOrigin
{
    /**
     * Hosts browsers treat as a secure context without TLS.
     */
    private const LoopbackHosts = ['localhost', '127.0.0.1', '[::1]'];

    /**
     * Passkeys are bound to a relying party ID (a domain), and the browser refuses to run
     * a ceremony unless the page it runs on belongs to that domain. The server then checks
     * the origin a second time against a list built from APP_URL, which rejects requests
     * the browser considered perfectly valid whenever the two disagree on scheme or port -
     * a panel reached on a non-standard port, for example.
     *
     * Accept the request's own origin when its host is exactly the relying party ID. The
     * browser has already guaranteed the domain matches, so this gives nothing away, while
     * an origin on any other domain stays rejected.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = PasskeyOrigin::normalize($request->headers->get('Origin') ?? $request->getSchemeAndHttpHost());

        if ($origin !== null && $this->belongsToRelyingParty($origin)) {
            config(['passkeys.allowed_origins' => PasskeyOrigin::all([
                ...config('passkeys.allowed_origins'),
                $origin,
            ])]);
        }

        return $next($request);
    }

    /**
     * Determine whether an origin is one a browser could have produced for our relying
     * party: the domain has to be the relying party ID itself, and the page has to have
     * been a secure context, since that is the only place the WebAuthn API exists.
     */
    private function belongsToRelyingParty(string $origin): bool
    {
        $parts = parse_url($origin);
        $host = $parts['host'] ?? null;
        $scheme = $parts['scheme'] ?? null;

        if ($host !== Passkeys::relyingPartyId()) {
            return false;
        }

        return $scheme === 'https' || ($scheme === 'http' && in_array($host, self::LoopbackHosts, true));
    }
}
