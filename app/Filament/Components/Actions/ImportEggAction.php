<?php

namespace App\Filament\Components\Actions;

use App\Console\Commands\Egg\UpdateEggIndexCommand;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Closure;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
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

        $this->label(trans('filament-actions::import.modal.actions.import.label'));

        $this->iconButton();

        $this->icon('tabler-file-import');

        $this->iconSize(IconSize::ExtraLarge);

        $this->authorize(fn () => user()?->can('import egg'));

        $this->action(function (array $data, EggImporterService $eggImportService): void {
            $eggs = array_merge(collect($data['urls'])->flatten()->whereNotNull()->unique()->all(), Arr::wrap($data['files']));
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
                    Tab::make('file')
                        ->label(trans('admin/egg.import.file'))
                        ->icon('tabler-file-upload')
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
                        ->icon('tabler-world-upload')
                        ->schema([
                            Select::make('github')
                                ->label(trans('admin/egg.import.github'))
                                ->options(fn () => cache('eggs.index'))
                                ->selectablePlaceholder(false)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->hintAction(
                                    Action::make('refresh')
                                        ->iconButton()
                                        ->icon('tabler-refresh')
                                        ->tooltip(trans('admin/egg.import.refresh'))
                                        ->action(function () {
                                            Artisan::call(UpdateEggIndexCommand::class);
                                        })
                                )
                                ->afterStateUpdated(function ($state, Set $set, Get $get) use ($isMultiple) {
                                    if ($state) {
                                        $urls = $isMultiple ? $get('urls') : [];
                                        $urls[Str::uuid()->toString()] = ['url' => $state];
                                        $set('urls', $urls);
                                        $set('github', null);
                                    }
                                }),
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
}
