<?php

namespace App\Filament\Server\Resources\FileResource\Pages;

use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;
use App\Enums\EditorLanguages;
use App\Facades\Activity;
use App\Filament\Server\Resources\FileResource;
use App\Models\File;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Panel;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\PageRegistration;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Locked;

/**
 * @property Form $form
 */
class EditFiles extends Page
{
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static string $resource = FileResource::class;

    protected static string $view = 'filament.server.pages.edit-file';

    protected static ?string $title = '';

    #[Locked]
    public string $path;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        File::get($server, dirname($this->path))->orderByDesc('is_directory')->orderBy('name');

        return $form
            ->schema([
                Select::make('lang')
                    ->live()
                    ->label('')
                    ->placeholder('File Language')
                    ->options(EditorLanguages::class)
                    ->hidden() //TODO Fix Dis
                    ->default(function () {
                        $ext = pathinfo($this->path, PATHINFO_EXTENSION);

                        if ($ext === 'yml') {
                            return 'yaml';
                        }

                        return $ext;
                    }),
                Section::make('Editing: ' . $this->path)
                    ->footerActions([
                        Action::make('save')
                            ->label('Save')
                            ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                            ->icon('tabler-device-floppy')
                            ->keyBindings('mod+s')
                            ->action(function (DaemonFileRepository $fileRepository) use ($server) {
                                $data = $this->form->getState();

                                $fileRepository
                                    ->setServer($server)
                                    ->putContent($this->path, $data['editor'] ?? '');

                                Activity::event('server:file.write')
                                    ->property('file', $this->path)
                                    ->log();

                                Notification::make()
                                    ->success()
                                    ->duration(5000) // 5 seconds
                                    ->title('Saved File')
                                    ->body(fn () => $this->path)
                                    ->send();

                                $this->redirect(ListFiles::getUrl(['path' => dirname($this->path)]));
                            }),
                        Action::make('cancel')
                            ->label('Cancel')
                            ->color('danger')
                            ->icon('tabler-x')
                            ->url(fn () => ListFiles::getUrl(['path' => dirname($this->path)])),
                    ])
                    ->footerActionsAlignment(Alignment::End)
                    ->schema([
                        MonacoEditor::make('editor')
                            ->label('')
                            ->placeholderText('')
                            ->formatStateUsing(function (DaemonFileRepository $fileRepository) use ($server) {
                                try {
                                    return $fileRepository
                                        ->setServer($server)
                                        ->getContent($this->path, config('panel.files.max_edit_size'));
                                } catch (FileNotFoundException) {
                                    abort(404, $this->path . ' not found.');
                                }
                            })
                            ->language(fn (Get $get) => $get('lang') ?? 'plaintext')
                            ->view('filament.plugins.monaco-editor'),
                    ]),
            ]);
    }

    public function mount(string $path): void
    {
        $this->authorizeAccess();

        $this->path = $path;

        $this->form->fill();
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
