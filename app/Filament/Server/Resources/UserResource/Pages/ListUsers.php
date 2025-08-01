<?php

namespace App\Filament\Server\Resources\UserResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\UserResource;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Subusers\SubuserCreationService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions as assignAll;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\Support\Htmlable;

class ListUsers extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = UserResource::class;

    /** @return array<Actions\Action|Actions\ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $tabs = [];
        $permissionsArray = [];

        foreach (Permission::permissionData() as $data) {
            $options = [];
            $descriptions = [];

            foreach ($data['permissions'] as $permission) {
                $options[$permission] = str($permission)->headline();
                $descriptions[$permission] = trans('server/user.permissions.' . $data['name'] . '_' . str($permission)->replace('-', '_'));
                $permissionsArray[$data['name']][] = $permission;
            }

            $tabs[] = Tab::make(str($data['name'])->headline())
                ->schema([
                    Section::make()
                        ->description(trans('server/user.permissions.' . $data['name'] . '_desc'))
                        ->icon($data['icon'])
                        ->schema([
                            CheckboxList::make($data['name'])
                                ->label('')
                                ->bulkToggleable()
                                ->columns(2)
                                ->options($options)
                                ->descriptions($descriptions),
                        ]),
                ]);
        }

        return [
            Actions\CreateAction::make('invite')
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-user-plus')
                ->tooltip(trans('server/user.invite_user'))
                ->createAnother(false)
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_USER_CREATE, $server))
                ->form([
                    Grid::make()
                        ->columnSpanFull()
                        ->columns([
                            'default' => 1,
                            'sm' => 1,
                            'md' => 5,
                            'lg' => 6,
                        ])
                        ->schema([
                            TextInput::make('email')
                                ->label(trans('server/user.email'))
                                ->email()
                                ->inlineLabel()
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 4,
                                    'lg' => 5,
                                ])
                                ->required(),
                            assignAll::make([
                                Action::make('assignAll')
                                    ->label(trans('server/user.assign_all'))
                                    ->action(function (Set $set, Get $get) use ($permissionsArray) {
                                        $permissions = $permissionsArray;
                                        foreach ($permissions as $key => $value) {
                                            $allValues = array_unique($value);
                                            $set($key, $allValues);
                                        }
                                    }),
                            ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                ]),
                            Tabs::make()
                                ->columnSpanFull()
                                ->schema($tabs),
                        ]),
                ])
                ->modalHeading(trans('server/user.invite_user'))
                ->modalSubmitActionLabel(trans('server/user.action'))
                ->action(function (array $data, SubuserCreationService $service) use ($server) {
                    $email = strtolower($data['email']);

                    $permissions = collect($data)
                        ->forget('email')
                        ->flatMap(fn ($permissions, $key) => collect($permissions)->map(fn ($permission) => "$key.$permission"))
                        ->push(Permission::ACTION_WEBSOCKET_CONNECT)
                        ->unique()
                        ->all();

                    try {
                        $subuser = $service->handle($server, $email, $permissions);

                        Activity::event('server:subuser.create')
                            ->subject($subuser->user)
                            ->property([
                                'email' => $data['email'],
                                'permissions' => $permissions,
                            ]);

                        Notification::make()
                            ->title(trans('server/user.notification_add'))
                            ->success()
                            ->send();
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title(trans('server/user.notification_failed'))
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }

                    return redirect(self::getUrl(tenant: $server));
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string|Htmlable
    {
        return trans('server/user.title');
    }
}
