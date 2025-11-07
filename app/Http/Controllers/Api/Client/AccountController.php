<?php

namespace App\Http\Controllers\Api\Client;

use App\Facades\Activity;
use App\Http\Requests\Api\Client\Account\UpdateEmailRequest;
use App\Http\Requests\Api\Client\Account\UpdatePasswordRequest;
use App\Http\Requests\Api\Client\Account\UpdateUsernameRequest;
use App\Services\Users\UserUpdateService;
use App\Transformers\Api\Client\UserTransformer;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class AccountController extends ClientApiController
{
    /**
     * AccountController constructor.
     */
    public function __construct(private AuthManager $manager, private UserUpdateService $updateService)
    {
        parent::__construct();
    }

    /**
     * View account
     *
     * @return array<array-key, mixed>
     */
    public function index(Request $request): array
    {
        return $this->fractal->item($request->user())
            ->transformWith($this->getTransformer(UserTransformer::class))
            ->toArray();
    }

    /**
     * Update username
     *
     * Update the authenticated user's username.
     */
    public function updateUsername(UpdateUsernameRequest $request): JsonResponse
    {
        $original = $request->user()->username;
        $this->updateService->handle($request->user(), $request->validated());

        if ($original !== $request->input('username')) {
            Activity::event('user:account.username-changed')
                ->property(['old' => $original, 'new' => $request->input('username')])
                ->log();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Update email
     *
     * Update the authenticated user's email address.
     */
    public function updateEmail(UpdateEmailRequest $request): JsonResponse
    {
        $original = $request->user()->email;
        $this->updateService->handle($request->user(), $request->validated());

        if ($original !== $request->input('email')) {
            Activity::event('user:account.email-changed')
                ->property(['old' => $original, 'new' => $request->input('email')])
                ->log();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Update password
     *
     * Update the authenticated user's password. All existing sessions will be logged
     * out immediately.
     *
     * @throws Throwable
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $this->updateService->handle($request->user(), $request->validated());

        $guard = $this->manager->guard();
        // If you do not update the user in the session you'll end up working with a
        // cached copy of the user that does not include the updated password. Do this
        // to correctly store the new user details in the guard and allow the logout
        // other devices functionality to work.
        $guard->setUser($user);

        if ($guard instanceof SessionGuard) {
            $guard->logoutOtherDevices($request->input('password'));
        }

        Activity::event('user:account.password-changed')->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
