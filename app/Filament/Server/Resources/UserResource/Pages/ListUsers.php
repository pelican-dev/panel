<?php

namespace App\Filament\Server\Resources\UserResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\UserResource;
use App\Models\Permission;
use App\Models\Server;
use App\Models\User;
use App\Services\Subusers\SubuserCreationService;
use Exception;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions as assignAll;
use Filament\Forms\Components\Actions\Action;
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

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $tabs = [];
        $permissionsArray = [];
        foreach (Permission::permissionTabs() as $tab) {
            $options = [];
            $descriptions = [];
            foreach ($tab['checkboxList']['options'] as $option) {
                $options[$option['label']] = $option['name'];
                $descriptions[$option['label']] = $option['description'];
                $permissionsArray[$tab['checkboxList']['name']][] = $option['label'];
            }

            if ($tab['checkboxList']['name'] == 'control') {
                $tabs[] = Tab::make($tab['name'])
                    ->schema([
                        Section::make()
                            ->description($tab['description'])
                            ->icon($tab['icon'])
                            ->schema([
                                CheckboxList::make($tab['checkboxList']['name'])
                                    ->formatStateUsing(function (User $user, Set $set) use ($server) {
                                        $permissionsArray = $server->subusers->where('user_id', $user->id)->first()->permissions;

                                        $transformedPermissions = [];

                                        foreach ($permissionsArray as $permission) {
                                            [$group, $action] = explode('.', $permission, 2);
                                            $transformedPermissions[$group][] = $action;
                                        }

                                        foreach ($transformedPermissions as $key => $value) {
                                            $set($key, $value);
                                        }

                                        return $transformedPermissions['control'] ?? [];
                                    })
                                    ->bulkToggleable()
                                    ->label('')
                                    ->columns($tab['checkboxList']['columns'])
                                    ->options($options)
                                    ->descriptions($descriptions),
                            ]),
                    ]);
                continue;
            }
            $tabs[] = Tab::make($tab['name'])
                ->schema([
                    Section::make()
                        ->description($tab['description'])
                        ->icon($tab['icon'])
                        ->schema([
                            CheckboxList::make($tab['checkboxList']['name'])
                                ->bulkToggleable()
                                ->label('')
                                ->columns($tab['checkboxList']['columns'])
                                ->options($options)
                                ->descriptions($descriptions),
                        ]),
                ]);
        }

        return [
            Actions\CreateAction::make('invite')
                ->label('Invite User')
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
                                    ->label('Assign All')
                                    ->action(function (Set $set, Get $get) use ($permissionsArray) {
                                        $permissions = $permissionsArray;
                                        foreach ($permissions as $key => $value) {
                                            $currentValues = $get($key) ?? [];
                                            $allValues = array_unique(array_merge($currentValues, $value));
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
                ->modalHeading('Invite User')
                ->modalSubmitActionLabel('Invite')
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
                            ->title('User Invited!')
                            ->success()
                            ->send();
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('Failed')
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
}
