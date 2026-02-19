<?php

namespace App\Filament\Server\Pages;

use App\Enums\SubuserPermission;
use App\Enums\TablerIcon;
use App\Facades\Activity;
use App\Models\Mount;
use App\Models\Server;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Mounts extends ServerFormPage
{
    protected static string|BackedEnum|null $navigationIcon = TablerIcon::LayersLinked;

    protected static ?int $navigationSort = 11;

    public static function canAccess(): bool
    {
        return parent::canAccess() && user()?->can(SubuserPermission::MountRead, Filament::getTenant());
    }

    protected function authorizeAccess(): void
    {
        abort_unless(user()?->can(SubuserPermission::MountRead, Filament::getTenant()), 403);
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'mounts' => $this->getRecord()->mounts->pluck('id')->toArray(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        /** @var Server $server */
        $server = $this->getRecord();

        $allowedMounts = Mount::query()
            ->where('user_mountable', true)
            ->where(function ($query) use ($server) {
                $query->whereDoesntHave('nodes')
                    ->orWhereHas('nodes', fn ($q) => $q->where('nodes.id', $server->node_id));
            })
            ->where(function ($query) use ($server) {
                $query->whereDoesntHave('eggs')
                    ->orWhereHas('eggs', fn ($q) => $q->where('eggs.id', $server->egg_id));
            })
            ->get();

        return parent::form($schema)
            ->components([
                Section::make(trans('server/mount.description'))
                    ->schema([
                        CheckboxList::make('mounts')
                            ->hiddenLabel()
                            ->relationship('mounts')
                            ->options(fn () => $allowedMounts->mapWithKeys(fn (Mount $mount) => [$mount->id => $mount->name]))
                            ->descriptions(fn () => $allowedMounts->mapWithKeys(fn (Mount $mount) => [$mount->id => "$mount->source -> $mount->target"]))
                            ->helperText(fn () => $allowedMounts->isEmpty() ? trans('server/mount.no_mounts') : null)
                            ->disabled(fn (Server $server) => !user()?->can(SubuserPermission::MountUpdate, $server))
                            ->bulkToggleable()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->save();
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var Server $server */
        $server = $this->getRecord();

        abort_unless(user()?->can(SubuserPermission::MountUpdate, $server), 403);

        try {
            $this->form->getState();
            $this->form->saveRelationships();

            Activity::event('server:mount.update')
                ->log();

            Notification::make()
                ->title(trans('server/mount.notification_updated'))
                ->success()
                ->send();
        } catch (\Exception $exception) {
            Notification::make()
                ->title(trans('server/mount.notification_failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getTitle(): string
    {
        return trans('server/mount.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/mount.title');
    }
}
