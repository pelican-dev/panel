<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\View\Factory as ViewFactory;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Translation\Translator;
use App\Services\Users\UserUpdateService;
use App\Traits\Helpers\AvailableLanguages;
use App\Services\Users\UserCreationService;
use App\Http\Requests\Admin\UserFormRequest;
use App\Http\Requests\Admin\NewUserFormRequest;

class UserController extends Controller
{
    use AvailableLanguages;

    /**
     * UserController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected UserCreationService $creationService,
        protected Translator $translator,
        protected UserUpdateService $updateService,
        protected ViewFactory $view
    ) {
    }

    /**
     * Display user index page.
     */
    public function index(): View
    {
        $users = QueryBuilder::for(
            User::query()->select('users.*')
                ->selectRaw('COUNT(DISTINCT(subusers.id)) as subuser_of_count')
                ->selectRaw('COUNT(DISTINCT(servers.id)) as servers_count')
                ->leftJoin('subusers', 'subusers.user_id', '=', 'users.id')
                ->leftJoin('servers', 'servers.owner_id', '=', 'users.id')
                ->groupBy('users.id')
        )
            ->allowedFilters(['username', 'email', 'uuid'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate(50);

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Display new user page.
     */
    public function create(): View
    {
        return view('admin.users.new', [
            'languages' => $this->getAvailableLanguages(),
        ]);
    }

    /**
     * Display user view page.
     */
    public function view(User $user): View
    {
        return view('admin.users.view', [
            'user' => $user,
            'languages' => $this->getAvailableLanguages(),
        ]);
    }

    /**
     * Delete a user from the system.
     *
     * @throws \Exception
     * @throws \App\Exceptions\DisplayException
     */
    public function delete(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users');
    }

    /**
     * Create a user.
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(NewUserFormRequest $request): RedirectResponse
    {
        $user = $this->creationService->handle($request->normalize());
        $this->alert->success($this->translator->get('admin/user.notices.account_created'))->flash();

        return redirect()->route('admin.users.view', $user->id);
    }

    /**
     * Update a user on the system.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(UserFormRequest $request, User $user): RedirectResponse
    {
        $this->updateService
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle($user, $request->normalize());

        $this->alert->success(trans('admin/user.notices.account_updated'))->flash();

        return redirect()->route('admin.users.view', $user->id);
    }

    /**
     * Get a JSON response of users on the system.
     */
    public function json(Request $request): JsonResponse
    {
        // Handle single user requests | TODO: Separate this out into its own method
        if ($userId = $request->query('user_id')) {
            $user = User::query()->findOrFail($userId);
            $user['md5'] = md5(strtolower($user->email));

            return response()->json($user);
        }

        // Handle all users list
        $userPaginator = QueryBuilder::for(User::query())->allowedFilters(['email'])->paginate(25);

        /** @var User[] $users */
        $users = $userPaginator->items();

        return response()->json(collect($users)->map(function (User $user) {
            $user['md5'] = md5(strtolower($user->email));

            return $user;
        }));
    }
}
