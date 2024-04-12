<?php

namespace App\Http\Middleware;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Auth\FailedCaptcha;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyReCaptcha
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        if (!config('recaptcha.enabled')) {
            return $next($request);
        }

        if (app()->isLocal()) {
            return $next($request);
        }

        if ($request->filled('g-recaptcha-response')) {
            $client = new Client();
            $res = $client->post(config('recaptcha.domain'), [
                'form_params' => [
                    'secret' => config('recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                ],
            ]);

            if ($res->getStatusCode() === 200) {
                $result = json_decode($res->getBody());

                if ($result->success && (!config('recaptcha.verify_domain') || $this->isResponseVerified($result, $request))) {
                    return $next($request);
                }
            }
        }

        event(new FailedCaptcha($request->ip(), $result->hostname ?? null));

        throw new HttpException(Response::HTTP_BAD_REQUEST, 'Failed to validate reCAPTCHA data.');
    }

    /**
     * Determine if the response from the recaptcha servers was valid.
     */
    private function isResponseVerified(\stdClass $result, Request $request): bool
    {
        if (!config('recaptcha.verify_domain')) {
            return false;
        }

        $url = parse_url($request->url());

        return $result->hostname === array_get($url, 'host');
    }
}
