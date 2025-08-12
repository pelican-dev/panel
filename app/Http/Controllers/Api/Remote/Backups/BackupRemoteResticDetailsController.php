<?php

namespace App\Http\Controllers\Api\Remote\Backups;

use App\Exceptions\Http\HttpForbiddenException;
use App\Extensions\Backups\BackupManager;
use App\Extensions\Filesystem\ResticFilesystem;
use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\Node;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class BackupRemoteResticDetailsController extends Controller
{
    /**
     * BackupRemoteResticDetailsController constructor.
     */
    public function __construct(private BackupManager $backupManager) {}

    /**
     * Returns the details required to create backups with restic.
     *
     * @throws Exception
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function __invoke(Request $request, string $backup): JsonResponse
    {
        // Get the node associated with the request.
        /** @var Node $node */
        $node = $request->attributes->get('node');

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

        // Ensure we are using the Restic adapter.
        $adapter = $this->backupManager->adapter();
        if (!$adapter instanceof ResticFilesystem) {
            throw new BadRequestHttpException('The configured backup adapter is not a Restic compatible adapter.');
        }

        return new JsonResponse($adapter->getResticInfo());
    }
}
