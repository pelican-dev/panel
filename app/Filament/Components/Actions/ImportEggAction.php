<?php

namespace App\Filament\Components\Actions;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Closure;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class ImportEggAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('filament-actions::import.modal.actions.import.label'));

        $this->authorize(fn () => auth()->user()->can('import egg'));

        $this->action(function (array $data, EggImporterService $eggImportService): void {
            [$success, $failed] = [collect(), collect()];

            if (!empty($data['egg'])) {
                $eggFiles = $data['egg'];

                foreach ($eggFiles as $file) {
                    $name = str($file)->afterLast('/')->before('.json');
                    try {
                        $eggImportService->fromFile($file);
                        $success->push($name);
                    } catch (Exception $exception) {
                        $failed->push($name);
                        report($exception);
                    }
                }
            }

            if (!empty($data['urls'])) {
                $eggUrls = collect($data['urls'])->flatten()->unique()->all();

                foreach ($eggUrls as $url) {
                    $name = str($url)->afterLast('/')->before('.json');
                    try {
                        $eggImportService->fromUrl($url);
                        $success->push($name);
                    } catch (Exception $exception) {
                        $failed->push($name);
                        report($exception);
                    }
                }
            }

            if (count($failed) > 0) {
                Notification::make()
                    ->title(trans('admin/egg.import.import_failed'))
                    ->body($failed->join(', '))
                    ->danger()
                    ->send();
            }
            if (count($success) > 0) {
                Notification::make()
                    ->title(trans('admin/egg.import.import_success'))
                    ->body($success->join(', '))
                    ->success()
                    ->send();
            }
        });
    }

    public function multiple(bool|Closure $condition = true): static
    {
        $isMultiple = (bool) $this->evaluate($condition);
        $this->form([
            Tabs::make('Tabs')
                ->contained(false)
                ->tabs([
                    Tab::make(trans('admin/egg.import.file'))
                        ->icon('tabler-file-upload')
                        ->schema([
                            FileUpload::make('egg')
                                ->label('Egg')
                                ->hint(trans('admin/egg.import.egg_help'))
                                ->acceptedFileTypes(['application/json'])
                                ->storeFiles(false)
                                ->multiple($isMultiple),
                        ]),
                    Tab::make(trans('admin/egg.import.url'))
                        ->icon('tabler-world-upload')
                        ->schema([
                            Repeater::make('urls')
                                ->itemLabel(fn (array $state) => $state['url'] ? str($state['url'])->afterLast('/')->before('.json') : null)
                                ->hint(trans('admin/egg.import.url_help'))
                                ->addActionLabel(trans('admin/egg.import.add_url'))
                                ->grid($isMultiple ? 2 : null)
                                ->reorderable(false)
                                ->addable($isMultiple)
                                ->deletable(fn (array $state) => count($state) > 1)
                                ->schema([
                                    TextInput::make('url')
                                        ->default(fn (Egg $egg) => $egg->update_url)
                                        ->live()
                                        ->label(trans('admin/egg.import.url'))
                                        ->placeholder('https://raw.githubusercontent.com/pelican-eggs/generic/main/nodejs/egg-node-js-generic.json')
                                        ->url()
                                        ->required(),
                                ]),
                        ]),
                ]),
        ]);

        return $this;
    }
}
