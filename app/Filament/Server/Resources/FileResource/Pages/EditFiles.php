<?php

namespace App\Filament\Server\Resources\FileResource\Pages;

use App\Enums\EditorLanguages;
use App\Facades\Activity;
use App\Filament\Server\Resources\FileResource;
use App\Livewire\AlertBanner;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Panel;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\PageRegistration;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Locked;

/**
 * @property Form $form
 */
class EditFiles extends Page
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static string $resource = FileResource::class;

    protected string $view = 'filament.server.pages.edit-file';

    protected static ?string $title = '';

    #[Locked]
    public string $path;

    private DaemonFileRepository $fileRepository;

    public ?array $data = [];

    public function form(Form|Schema $schema): Schema
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        Activity::event('server:file.read')
            ->property('file', $this->path)
            ->log();

        return $schema
            ->schema([
                Section::make('Editing: ' . $this->path)
                    ->footerActions([
                        Action::make('save_and_close')
                            ->label('Save & Close')
                            ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                            ->icon('tabler-device-floppy')
                            ->keyBindings('mod+shift+s')
                            ->action(function () {
                                $this->getDaemonFileRepository()->putContent($this->path, $this->data['editor'] ?? '');

                                Activity::event('server:file.write')
                                    ->property('file', $this->path)
                                    ->log();

                                Notification::make()
                                    ->success()
                                    ->title('File saved')
                                    ->body(fn () => $this->path)
                                    ->send();

                                $this->redirect(ListFiles::getUrl(['path' => dirname($this->path)]));
                            }),
                        Action::make('save')
                            ->label('Save')
                            ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                            ->icon('tabler-device-floppy')
                            ->keyBindings('mod+s')
                            ->action(function () {
                                $this->getDaemonFileRepository()->putContent($this->path, $this->data['editor'] ?? '');

                                Activity::event('server:file.write')
                                    ->property('file', $this->path)
                                    ->log();

                                Notification::make()
                                    ->success()
                                    ->title('File saved')
                                    ->body(fn () => $this->path)
                                    ->send();
                            }),
                        Action::make('cancel')
                            ->label('Cancel')
                            ->color('danger')
                            ->icon('tabler-x')
                            ->url(fn () => ListFiles::getUrl(['path' => dirname($this->path)])),
                    ])
                    ->footerActionsAlignment(Alignment::End)
                    ->schema([
                        Select::make('lang')
                            ->label('Syntax Highlighting')
                            ->searchable()
                            ->native(false)
                            ->live()
                            ->options(EditorLanguages::class)
                            ->selectablePlaceholder(false)
                            ->afterStateUpdated(fn ($state) => $this->dispatch('setLanguage', lang: $state))
                            ->default(fn () => EditorLanguages::fromWithAlias(pathinfo($this->path, PATHINFO_EXTENSION))),
                        //                         TODO MonacoEditor::make('editor')
                        //                            ->hiddenLabel()
                        //                            ->showPlaceholder(false)
                        //                                        ->danger()
                        //                                        ->body('<code>' . $this->path . '</code> Max is ' . convert_bytes_to_readable(config('panel.files.max_edit_size')))
                        //                                        ->title('File too large!')
                        //                                    AlertBanner::make()
                        //                                } catch (FileSizeTooLargeException) {
                        //                                try {
                        //                            ->default(function () {
                        //                                    return $this->getDaemonFileRepository()->getContent($this->path, config('panel.files.max_edit_size'));
                        //                                        ->closable()
                        //                                        ->send();
                        //
                        //                                } catch (FileNotFoundException) {
                        //                                    AlertBanner::make()
                        //                                    $this->redirect(ListFiles::getUrl());
                        //                                        ->title('File Not found!')
                        //                                        ->danger()
                        //                                        ->body('<code>' . $this->path . '</code>')
                        //                                        ->send();
                        //                                        ->closable()
                        //
                        //                                    $this->redirect(ListFiles::getUrl());
                        //                                } catch (FileNotEditableException) {
                        //                                        ->title('Could not edit directory!')
                        //                                    AlertBanner::make()
                        //                                        ->body('<code>' . $this->path . '</code>')
                        //                                        ->closable()
                        //                                        ->send();
                        //                                        ->danger()
                        //
                        //                                }
                        //                                    $this->redirect(ListFiles::getUrl());
                        //                            })
                        //                            ->language(fn (Get $get) => $get('lang'))
                        //                            ->view('filament.plugins.monaco-editor'),
                    ]),
            ]);
    }

    public function mount(string $path): void
    {
        $this->authorizeAccess();

        $this->path = $path;

        $this->form->fill();

        if (str($path)->endsWith('.pelicanignore')) {
            AlertBanner::make('.pelicanignore_info')
                ->title('You\'re editing a <code>.pelicanignore</code> file!')
                ->body('Any files or directories listed in here will be excluded from backups. Wildcards are supported by using an asterisk (<code>*</code>).<br>You can negate a prior rule by prepending an exclamation point (<code>!</code>).')
                ->info()
                ->closable()
                ->send();
        }
    }

    protected function authorizeAccess(): void
    {
        abort_unless(auth()->user()->can(Permission::ACTION_FILE_READ_CONTENT, Filament::getTenant()), 403);
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(static::getResource()::form(
                $this->makeForm()
                    ->statePath($this->getFormStatePath())
                    ->columns($this->hasInlineLabels() ? 1 : 2)
                    ->inlineLabel($this->hasInlineLabels()),
            )),
        ];
    }

    public function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
        ];

        $previousParts = '';
        foreach (explode('/', $this->path) as $part) {
            $previousParts = $previousParts . '/' . $part;
            $breadcrumbs[self::getUrl(['path' => ltrim($previousParts, '/')])] = $part;
        }

        return $breadcrumbs;
    }

    private function getDaemonFileRepository(): DaemonFileRepository
    {
        /** @var Server $server */
        $server = Filament::getTenant();
        $this->fileRepository ??= (new DaemonFileRepository())->setServer($server);

        return $this->fileRepository;
    }

    public static function route(string $path): PageRegistration
    {
        return new PageRegistration(
            page: static::class,
            route: fn (Panel $panel): Route => RouteFacade::get($path, static::class)
                ->middleware(static::getRouteMiddleware($panel))
                ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
                ->where('path', '.*'),
        );
    }
}
