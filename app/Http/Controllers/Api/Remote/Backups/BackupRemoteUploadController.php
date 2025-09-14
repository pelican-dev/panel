<?php

namespace App\Http\Controllers\Api\Remote\Backups;

use App\Exceptions\Http\HttpForbiddenException;
use App\Extensions\Backups\BackupManager;
use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\Node;
use App\Models\Server;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
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
        }

        // Check that the backup is "owned" by the node making the request. This avoids other nodes
        // from messing with backups that they don't own.
        /** @var Server $server */
        $server = $model->server;
        if ($server->node_id !== $node->id) {
            throw new HttpForbiddenException('You do not have permission to access that backup.');
        }

        // Prevent backups that have already been completed from trying to
        // be uploaded again.
        if (!is_null($model->completed_at)) {
            throw new ConflictHttpException('This backup is already in a completed state.');
        }

        return $this->backupManager
            ->adapter($adapter)
            ->provideUploadInfo($size, $model, $server);
    }
}
