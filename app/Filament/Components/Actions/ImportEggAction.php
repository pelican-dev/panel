<?php

namespace App\Filament\Components\Actions;

use App\Console\Commands\Egg\UpdateEggIndexCommand;
use App\Enums\TablerIcon;
use App\Jobs\InstallEgg;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Closure;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\Width;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImportEggAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tooltip(trans('filament-actions::import.modal.actions.import.label'));

        $this->hiddenLabel();

        $this->icon(TablerIcon::FileImport);

        $this->modalWidth(Width::ScreenExtraLarge);

        $this->authorize(fn () => user()?->can('import egg'));

        $this->action(function (array $data, EggImporterService $eggImportService): void {

            $gitHubEggs = array_get($this->data, 'eggs', []);
            $eggs = array_merge(collect($data['urls'])->flatten()->whereNotNull()->unique()->all(), Arr::wrap($data['files']));

            if ($gitHubEggs) {
                foreach ($gitHubEggs as $category => $sortedEggs) {
                    foreach ($sortedEggs as $downloadUrl) {
                        InstallEgg::dispatch($downloadUrl);
                    }
                }

                Notification::make()
                    ->title(trans('installer.egg.background_install_started'))
                    ->body(trans('installer.egg.background_install_description', ['count' => array_sum(array_map('count', $gitHubEggs))]))
                    ->success()
                    ->persistent()
                    ->send();

            }

            if (empty($eggs)) {
                return;
            }

            [$success, $failed] = [collect(), collect()];

            foreach ($eggs as $egg) {
                if ($egg instanceof TemporaryUploadedFile) {
                    $originalName = $egg->getClientOriginalName();
                    $filename = str($originalName)->afterLast('egg-');
                    $ext = str($originalName)->afterLast('.')->lower()->toString();

                    $name = match ($ext) {
                        'json' => $filename->before('.json')->headline(),
                        'yaml' => $filename->before('.yaml')->headline(),
                        'yml' => $filename->before('.yml')->headline(),
                        default => $filename->headline(),
                    };
                    $method = 'fromFile';
                } else {
                    $egg = str($egg);
                    $egg = $egg->contains('github.com') ? $egg->replaceFirst('blob', 'raw') : $egg;
                    $method = 'fromUrl';

                    $filename = $egg->afterLast('/egg-');
                    $ext = $filename->afterLast('.')->lower()->toString();

                    $name = match ($ext) {
                        'json' => $filename->before('.json')->headline(),
                        'yaml' => $filename->before('.yaml')->headline(),
                        'yml' => $filename->before('.yml')->headline(),
                        default => $filename->headline(),
                    };
                }
                try {
                    $eggImportService->$method($egg);
                    $success->push($name);
                } catch (Exception $exception) {
                    $failed->push($name);
                    report($exception);
                }
            }

            $bodyParts = collect([
                $success->isNotEmpty() ? trans('admin/egg.import.imported_eggs', ['eggs' => $success->join(', ')]) : null,
                $failed->isNotEmpty() ? trans('admin/egg.import.failed_import_eggs', ['eggs' => $failed->join(', ')]) : null,
            ])->filter();

            if ($bodyParts->isNotEmpty()) {
                Notification::make()
                    ->title(trans('admin/egg.import.import_result', [
                        'success' => $success->count(),
                        'failed' => $failed->count(),
                        'total' => $success->count() + $failed->count(),
                    ]))
                    ->body($bodyParts->join(' | '))
                    ->status($failed->isEmpty() ? 'success' : ($success->isEmpty() ? 'danger' : 'warning'))
                    ->send();
            }
        });
    }

    public function multiple(bool|Closure $condition = true): static
    {
        $isMultiple = (bool) $this->evaluate($condition);
        $this->schema([
            Tabs::make('Tabs')
                ->contained(false)
                ->tabs([
                    $this->importEggsFromGitHub(),
                    Tab::make('file')
                        ->label(trans('admin/egg.import.file'))
                        ->icon(TablerIcon::FileUpload)
                        ->schema([
                            FileUpload::make('files')
                                ->label(trans('admin/egg.model_label'))
                                ->hint(trans('admin/egg.import.egg_help'))
                                ->acceptedFileTypes(['application/json', 'application/x-yaml', 'text/yaml', '.yaml', '.yml'])
                                ->preserveFilenames()
                                ->previewable(false)
                                ->storeFiles(false)
                                ->multiple($isMultiple),
                        ]),
                    Tab::make('url')
                        ->label(trans('admin/egg.import.url'))
                        ->icon(TablerIcon::WorldUpload)
                        ->schema([
                            Repeater::make('urls')
                                ->hiddenLabel()
                                ->itemLabel(fn (array $state) => str($state['url'])->afterLast('/egg-')->beforeLast('.')->headline())
                                ->hint(trans('admin/egg.import.url_help'))
                                ->addActionLabel(trans('admin/egg.import.add_url'))
                                ->grid($isMultiple ? 2 : null)
                                ->reorderable(false)
                                ->addable($isMultiple)
                                ->deletable(fn (array $state) => count($state) > 1)
                                ->schema([
                                    TextInput::make('url')
                                        ->default(fn (?Egg $egg) => $egg->update_url ?? '')
                                        ->live()
                                        ->label(trans('admin/egg.import.url'))
                                        ->placeholder('https://github.com/pelican-eggs/generic/blob/main/nodejs/egg-node-js-generic.json')
                                        ->url()
                                        ->endsWith(['.json', '.yaml', '.yml'])
                                        ->validationAttribute(trans('admin/egg.import.url')),
                                ]),
                        ]),
                ]),
        ]);

        return $this;
    }

    public function importEggsFromGitHub(): Tab
    {
        if (!cache()->get('eggs.index')) {
            Artisan::call(UpdateEggIndexCommand::class);
        }

        $eggs = cache()->get('eggs.index', []);
        $categories = array_keys($eggs);
        $tabs = array_map(function (string $label) use ($eggs) {
            $id = str_slug($label, '_');
            $eggCount = count($eggs[$label]);

            return Tab::make($id)
                ->label($label)
                ->badge($eggCount)
                ->schema([
                    CheckboxList::make("eggs.$id")
                        ->hiddenLabel()
                        ->options(fn () => array_sort($eggs[$label]))
                        ->searchable($eggCount > 0)
                        ->bulkToggleable($eggCount > 0)
                        ->columns(4),
                ]);
        }, $categories);

        if (empty($tabs)) {
            $tabs[] = Tab::make('no_eggs')
                ->label(trans('installer.egg.no_eggs'))
                ->schema([
                    TextEntry::make('no_eggs')
                        ->hiddenLabel()
                        ->state(trans('installer.egg.exceptions.no_eggs')),
                ]);
        }

        return Tab::make('github')
            ->label(trans('admin/egg.import.github'))
            ->icon(TablerIcon::BrandGithub)
            ->columnSpanFull()
            ->schema([
                Tabs::make('egg_tabs')
                    ->tabs($tabs),
            ]);
    }
}
