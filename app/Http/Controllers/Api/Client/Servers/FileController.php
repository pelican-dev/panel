<?php

namespace App\Http\Controllers\Api\Client\Servers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Response;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use App\Facades\Activity;
use App\Services\Nodes\NodeJWTService;
use App\Repositories\Daemon\DaemonFileRepository;
use App\Transformers\Api\Client\FileObjectTransformer;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Http\Requests\Api\Client\Servers\Files\CopyFileRequest;
use App\Http\Requests\Api\Client\Servers\Files\PullFileRequest;
use App\Http\Requests\Api\Client\Servers\Files\ListFilesRequest;
use App\Http\Requests\Api\Client\Servers\Files\ChmodFilesRequest;
use App\Http\Requests\Api\Client\Servers\Files\DeleteFileRequest;
use App\Http\Requests\Api\Client\Servers\Files\RenameFileRequest;
use App\Http\Requests\Api\Client\Servers\Files\CreateFolderRequest;
use App\Http\Requests\Api\Client\Servers\Files\CompressFilesRequest;
use App\Http\Requests\Api\Client\Servers\Files\DecompressFilesRequest;
use App\Http\Requests\Api\Client\Servers\Files\GetFileContentsRequest;
use App\Http\Requests\Api\Client\Servers\Files\WriteFileContentRequest;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Client\ConnectionException;

#[Group('Server - File', weight: 0)]
class FileController extends ClientApiController
{
    /**
     * FileController constructor.
     */
    public function __construct(
        private NodeJWTService $jwtService,
        private DaemonFileRepository $fileRepository
    ) {
        parent::__construct();
    }

    /**
     * List files
     *
     * Returns a listing of files in a given directory.
     *
     * @return array<array-key, mixed>
     *
     * @throws ConnectionException
     */
    public function directory(ListFilesRequest $request, Server $server): array
    {
        $contents = $this->fileRepository
            ->setServer($server)
            ->getDirectory($request->get('directory') ?? '/');

        return $this->fractal->collection($contents)
            ->transformWith($this->getTransformer(FileObjectTransformer::class))
            ->toArray();
    }

    /**
     * View file
     *
     * Return the contents of a specified file for the user.
     *
     * @throws \Throwable
     */
    public function contents(GetFileContentsRequest $request, Server $server): Response
    {
        $response = $this->fileRepository->setServer($server)->getContent(
            $request->get('file'),
            config('panel.files.max_edit_size')
        );

        Activity::event('server:file.read')
            ->property('file', $request->get('file'))
            ->log();

        return new Response($response, Response::HTTP_OK, ['Content-Type' => 'text/plain']);
    }

    /**
     * Download file
     *
     * Generates a one-time token with a link that the user can use to download a given file.
     *
     * @return array<array-key, mixed>
     *
     * @throws \Throwable
     */
    public function download(GetFileContentsRequest $request, Server $server): array
    {
        $token = $this->jwtService
            ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
            ->setUser($request->user())
            ->setClaims([
                'file_path' => rawurldecode($request->get('file')),
                'server_uuid' => $server->uuid,
            ])
            ->handle($server->node, $request->user()->id . $server->uuid);

        Activity::event('server:file.download')->property('file', $request->get('file'))->log();

        return [
            'object' => 'signed_url',
            'attributes' => [
                'url' => sprintf(
                    '%s/download/file?token=%s',
                    $server->node->getConnectionAddress(),
                    $token->toString()
                ),
            ],
        ];
    }

    /**
     * Write file
     *
     * Writes the contents of the specified file to the server.
     *
     * @throws ConnectionException
     */
    public function write(WriteFileContentRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository
            ->setServer($server)
            ->putContent($request->get('file'), $request->getContent());

        Activity::event('server:file.write')
            ->property('file', $request->get('file'))
            ->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Create directory
     *
     * Creates a new folder on the server.
     *
     * @throws \Throwable
     */
    public function create(CreateFolderRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository
            ->setServer($server)
            ->createDirectory($request->input('name'), $request->input('root', '/'));

        Activity::event('server:file.create-directory')
            ->property('name', $request->input('name'))
            ->property('directory', $request->input('root'))
            ->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Rename file
     *
     * Renames a file on the remote machine.
     *
     * @throws \Throwable
     */
    public function rename(RenameFileRequest $request, Server $server): JsonResponse
    {
        $files = $request->input('files');

        $this->fileRepository
            ->setServer($server)
            ->renameFiles($request->input('root'), $files);

        Activity::event('server:file.rename')
            ->property('directory', $request->input('root'))
            ->property('files', $files)
            ->property('to', $files['to'])
            ->property('from', $files['from'])
            ->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Copy file
     *
     * Copies a file on the server.
     *
     * @throws ConnectionException
     */
    public function copy(CopyFileRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository
            ->setServer($server)
            ->copyFile($request->input('location'));

        Activity::event('server:file.copy')
            ->property('file', $request->input('location'))
            ->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Compress files
     *
     * @return array<array-key, mixed>
     *
     * @throws ConnectionException
     */
    public function compress(CompressFilesRequest $request, Server $server): array
    {
        $file = $this->fileRepository->setServer($server)->compressFiles(
            $request->input('root'),
            $request->input('files')
        );

        Activity::event('server:file.compress')
            ->property('directory', $request->input('root'))
            ->property('files', $request->input('files'))
            ->log();

        return $this->fractal->item($file)
            ->transformWith($this->getTransformer(FileObjectTransformer::class))
            ->toArray();
    }

    /**
     * Decompress files
     *
     * @throws ConnectionException
     */
    public function decompress(DecompressFilesRequest $request, Server $server): JsonResponse
    {
        set_time_limit(300);

        $this->fileRepository->setServer($server)->decompressFile(
            $request->input('root'),
            $request->input('file')
        );

        Activity::event('server:file.decompress')
            ->property('directory', $request->input('root'))
            ->property('file', $request->input('file'))
            ->log();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Delete files/ folders
     *
     * Deletes files or folders for the server in the given root directory.
     *
     * @throws ConnectionException
     */
    public function delete(DeleteFileRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository->setServer($server)->deleteFiles(
            $request->input('root'),
            $request->input('files')
        );

        Activity::event('server:file.delete')
            ->property('directory', $request->input('root'))
            ->property('files', $request->input('files'))
            ->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Update file permissions
     *
     * Updates file permissions for file(s) in the given root directory.
     *
     * @throws ConnectionException
     */
    public function chmod(ChmodFilesRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository->setServer($server)->chmodFiles(
            $request->input('root'),
            $request->input('files')
        );

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Pull remote file
     *
     * Requests that a file be downloaded from a remote location by daemon.
     *
     * @throws \Throwable
     */
    public function pull(PullFileRequest $request, Server $server): JsonResponse
    {
        $this->fileRepository->setServer($server)->pull(
            $request->input('url'),
            $request->input('directory'),
            $request->safe(['filename', 'use_header', 'foreground'])
        );

        Activity::event('server:file.pull')
            ->property('directory', $request->input('directory'))
            ->property('url', $request->input('url'))
            ->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
