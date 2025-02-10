<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('identifier')->default(ApiKey::generateTokenIdentifier(ApiKey::TYPE_APPLICATION)),
                Hidden::make('token')->default(str_random(ApiKey::KEY_LENGTH)),

                Hidden::make('user_id')
                    ->default(auth()->user()->id)
                    ->required(),

                Hidden::make('key_type')
                    ->inlineLabel()
                    ->default(ApiKey::TYPE_APPLICATION)
                    ->required(),

                Fieldset::make('Permissions')
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->schema(
                        collect(ApiKey::getPermissionList())->map(fn ($resource) => ToggleButtons::make('permissions_' . $resource)
                            ->label(str($resource)->replace('_', ' ')->title())->inline()
                            ->options([
                                0 => trans('admin/apikey.permissions.none'),
                                1 => trans('admin/apikey.permissions.read'),
                                // 2 => 'Write', // Makes no sense to have write-only permissions when you can't read it?
                                3 => trans('admin/apikey.permissions.read_write'),
                            ])
                            ->icons([
                                0 => 'tabler-book-off',
                                1 => 'tabler-book',
                                2 => 'tabler-writing',
                                3 => 'tabler-writing',
                            ])
                            ->colors([
                                0 => 'success',
                                1 => 'warning',
                                2 => 'danger',
                                3 => 'danger',
                            ])
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 1,
                            ])
                            ->default(0),
                        )->all(),
                    ),

                TagsInput::make('allowed_ips')
                    ->placeholder('127.0.0.1 or 192.168.1.1')
                    ->label(trans('admin/apikey.whitelist'))
                    ->helperText(trans('admin/apikey.whitelist_help'))
                    ->columnSpanFull(),

                Textarea::make('memo')
                    ->required()
                    ->label(trans('admin/apikey.description'))
                    ->helperText(trans('admin/apikey.description_help'))
                    ->columnSpanFull(),
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $permissions = [];

        foreach (ApiKey::getPermissionList() as $permission) {
            if (isset($data['permissions_' . $permission])) {
                $permissions[$permission] = intval($data['permissions_' . $permission]);
                unset($data['permissions_' . $permission]);
            }
        }

        $data['permissions'] = $permissions;

        return parent::handleRecordCreation($data);
    }
}
