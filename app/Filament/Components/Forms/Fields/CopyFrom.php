<?php

namespace App\Filament\Components\Forms\Fields;

use App\Models\Egg;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Livewire\Component;

class CopyFrom extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('admin/egg.copy_from'));

        $this->placeholder(trans('admin/egg.none'));

        $this->preload();

        $this->searchable();

        $this->live();
    }

    public function process(): static
    {
        $this->helperText(trans('admin/egg.copy_from_help'));

        $this->relationship('configFrom', 'name', ignoreRecord: true);

        $this->afterStateUpdated(function ($state, Set $set) {
            $set('copy_script_from', $state);
            if ($state === null) {
                $set('config_stop', '');
                $set('config_startup', '{}');
                $set('config_files', '{}');
                $set('config_logs', '{}');

                return;
            }
            $egg = Egg::find($state);
            $set('config_stop', $egg->config_stop);
            $set('config_startup', $egg->config_startup);
            $set('config_files', $egg->config_files);
            $set('config_logs', $egg->config_logs);
        });

        return $this;
    }

    public function script(): static
    {
        $this->relationship('scriptFrom', 'name', ignoreRecord: true);

        $this->afterStateUpdated(function ($state, Set $set, Component $livewire) {
            if ($state === null) {
                $set('script_container', 'ghcr.io/pelican-eggs/installers:debian');
                $set('script_entry', 'bash');
                $livewire->dispatch('setContent', content: '');

                return;
            }
            $egg = Egg::find($state);
            $set('script_container', $egg->script_container);
            $set('script_entry', $egg->script_entry);
            $livewire->dispatch('setContent', content: $egg->script_install);
        });

        return $this;
    }
}
