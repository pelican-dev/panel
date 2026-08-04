<?php

namespace App\Tests\Integration\Services;

use App\Services\Helpers\PluginService;
use App\Tests\Integration\IntegrationTestCase;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use ZipArchive;

class PluginServiceTest extends IntegrationTestCase
{
    private PluginService $service;

    /** @var string[] */
    private array $importedPlugins = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(PluginService::class);
    }

    protected function tearDown(): void
    {
        // Clean up anything the service extracted into the real plugins directory.
        foreach ($this->importedPlugins as $id) {
            File::deleteDirectory(plugin_path($id));
        }

        parent::tearDown();
    }

    public function test_import_uses_plugin_json_id_not_the_upload_filename(): void
    {
        // Zip contains a top-level folder "test-import-plugin", but the upload is renamed as
        // a browser would on a duplicate download.
        $file = $this->makeUpload('test-import-plugin(1).zip', [
            'test-import-plugin/plugin.json' => json_encode(['id' => 'test-import-plugin']),
        ]);

        $this->importedPlugins[] = 'test-import-plugin';

        $id = $this->service->downloadPluginFromFile($file);

        $this->assertSame('test-import-plugin', $id);
        $this->assertFileExists(plugin_path('test-import-plugin', 'plugin.json'));
        // The (1) suffixed folder must never be created.
        $this->assertDirectoryDoesNotExist(plugin_path('test-import-plugin(1)'));
    }

    public function test_import_handles_flat_zip_without_a_top_level_folder(): void
    {
        $file = $this->makeUpload('renamed(2).zip', [
            'plugin.json' => json_encode(['id' => 'test-flat-plugin']),
        ]);

        $this->importedPlugins[] = 'test-flat-plugin';

        $id = $this->service->downloadPluginFromFile($file);

        $this->assertSame('test-flat-plugin', $id);
        $this->assertFileExists(plugin_path('test-flat-plugin', 'plugin.json'));
    }

    public function test_import_without_a_manifest_fails_loudly(): void
    {
        $file = $this->makeUpload('not-a-plugin.zip', [
            'readme.txt' => 'nothing to see here',
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(trans('admin/plugin.notifications.import_no_manifest'));

        $this->service->downloadPluginFromFile($file);
    }

    public function test_import_with_a_non_string_id_fails_loudly(): void
    {
        $file = $this->makeUpload('bad-id.zip', [
            'plugin.json' => json_encode(['id' => []]),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(trans('admin/plugin.notifications.import_no_manifest'));

        $this->service->downloadPluginFromFile($file);
    }

    public function test_clean_download_keeps_the_existing_plugin_when_the_move_fails(): void
    {
        $this->importedPlugins[] = 'test-clean-plugin';

        $this->service->downloadPluginFromFile($this->makeUpload('test-clean-plugin.zip', [
            'test-clean-plugin/plugin.json' => json_encode(['id' => 'test-clean-plugin', 'version' => '1.0.0']),
        ]), true);

        // Force the move into plugins/<id> to fail, standing in for any filesystem-level
        // failure mid-replace. The set-aside and restore moves (to/from the .bak dir) run
        // for real so we can assert the original install survives.
        $real = new Filesystem();
        File::partialMock()
            ->shouldReceive('moveDirectory')
            ->andReturnUsing(function ($from, $to) use ($real) {
                if (str_ends_with($to, '.bak') || str_ends_with($from, '.bak')) {
                    return $real->moveDirectory($from, $to);
                }

                return false;
            });

        $file = $this->makeUpload('test-clean-plugin.zip', [
            'test-clean-plugin/plugin.json' => json_encode(['id' => 'test-clean-plugin', 'version' => '2.0.0']),
        ]);

        try {
            $this->service->downloadPluginFromFile($file, true);
            $this->fail('Expected the import to fail.');
        } catch (Exception) {
            // The original install must still be there after a failed clean download.
        }

        $this->assertFileExists(plugin_path('test-clean-plugin', 'plugin.json'));
        $this->assertDirectoryDoesNotExist(plugin_path('test-clean-plugin.bak'));
    }

    public function test_import_rejects_a_duplicate_plugin(): void
    {
        $this->importedPlugins[] = 'test-dupe-plugin';

        $this->service->downloadPluginFromFile($this->makeUpload('test-dupe-plugin.zip', [
            'test-dupe-plugin/plugin.json' => json_encode(['id' => 'test-dupe-plugin']),
        ]));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(trans('admin/plugin.notifications.import_exists'));

        $this->service->downloadPluginFromFile($this->makeUpload('test-dupe-plugin.zip', [
            'test-dupe-plugin/plugin.json' => json_encode(['id' => 'test-dupe-plugin']),
        ]));
    }

    /**
     * Build a zip on disk from the given [path => contents] map and wrap it in an UploadedFile.
     *
     * @param  array<string, string>  $entries
     */
    private function makeUpload(string $clientName, array $entries): UploadedFile
    {
        $tmpDir = TemporaryDirectory::make();
        $zipPath = $tmpDir->path('upload.zip');

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        foreach ($entries as $path => $contents) {
            $zip->addFromString($path, $contents);
        }
        $zip->close();

        // test mode (last arg) bypasses the is_uploaded_file() check.
        return new UploadedFile($zipPath, $clientName, 'application/zip', null, true);
    }
}
