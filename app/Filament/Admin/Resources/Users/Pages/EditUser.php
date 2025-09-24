<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Enums\CustomizationKey;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use App\Services\Users\UserUpdateService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = UserResource::class;

    private UserUpdateService $service;

    public function boot(UserUpdateService $service): void
    {
        $this->service = $service;
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(fn (User $user) => auth()->user()->id === $user->id ? trans('admin/user.self_delete') : ($user->servers()->count() > 0 ? trans('admin/user.has_servers') : trans('filament-actions::delete.single.modal.actions.delete.label')))
                ->disabled(fn (User $user) => auth()->user()->id === $user->id || $user->servers()->count() > 0),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof User) {
            return $record;
        }
        unset($data['roles']);

        return $this->service->handle($record, $data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $customization = [
            'console_font' => $data['console_font'],
            'console_font_size' => $data['console_font_size'],
            'console_rows' => $data['console_rows'],
            'console_graph_period' => $data['console_graph_period'],
            'dashboard_layout' => $data['dashboard_layout'],
            'top_navigation' => $data['top_navigation'],
        ];

        unset($data['console_font'],$data['console_font_size'], $data['console_rows'], $data['dashboard_layout'], $data['top_navigation']);

        $data['customization'] = json_encode($customization);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
//        $data['console_font'] = $this->getUser()->getCustomization(CustomizationKey::ConsoleFont);
//        $data['console_font_size'] = (int) $this->getUser()->getCustomization(CustomizationKey::ConsoleFontSize);
//        $data['console_rows'] = (int) $this->getUser()->getCustomization(CustomizationKey::ConsoleRows);
//        $data['console_graph_period'] = (int) $this->getUser()->getCustomization(CustomizationKey::ConsoleGraphPeriod);
//        $data['dashboard_layout'] = $this->getUser()->getCustomization(CustomizationKey::DashboardLayout);
//        $data['top_navigation'] = (bool) $this->getUser()->getCustomization(CustomizationKey::TopNavigation);

        return $data;
    }
}
