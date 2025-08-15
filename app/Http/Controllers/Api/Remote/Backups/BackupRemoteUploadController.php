<?php

namespace App\Http\Controllers\Api\Remote\Backups;

use App\Models\Node;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Backup;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Extensions\Backups\BackupManager;
use App\Exceptions\Http\HttpForbiddenException;
use Throwable;

class BackupRemoteUploadController extends Controller
{
    /**
     * BackupRemoteUploadController constructor.
     */
    public function __construct(private readonly BackupManager $backupManager) {}

    /**
     * Returns the required info to handle remote uploads that require extra information.
     *
     * @throws Exception
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function __invoke(Request $request, string $adapter, ?string $backup): JsonResponse
    {
        // Get the node associated with the request.
        /** @var Node $node */
        $node = $request->attributes->get('node');

        // Get the size query parameter.
        $size = (int) ($request->query('size') ?? 0);

        $model = null;
        $server = null;

        if ($backup === '0') {
            /** @var Backup $model */
            $model = Backup::query()
                ->where('uuid', $backup)
                ->firstOrFail();

            // Check that the backup is "owned" by the node making the request. This avoids other nodes
            // from messing with backups that they don't own.
            $server = $model->server;
            if ($server->node_id !== $node->id) {
                throw new HttpForbiddenException('You do not have permission to access that backup.');
            }
        }

        return $this->backupManager
            ->adapter($adapter)
            ->provideUploadInfo($size, $model, $server);
    }
}
