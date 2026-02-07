<?php

namespace App\Extensions\Filesystem;

use Aws\CommandInterface;
use Aws\Result;
use Aws\S3\S3ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use RuntimeException;
use SimpleXMLElement;

class S3Filesystem extends AwsS3V3Adapter
{
    /**
     * @param  array<mixed>  $options
     */
    public function __construct(
        private S3ClientInterface $client,
        private string $bucket,
        string $prefix = '',
        array $options = [],
    ) {
        parent::__construct(
            $client,
            $bucket,
            $prefix,
            null,
            null,
            $options,
        );
    }

    public function getClient(): S3ClientInterface
    {
        return $this->client;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * Execute an S3 command using a presigned URL for maximum compatibility
     * with S3-compatible providers.
     *
     * @return Result<array<string, mixed>>
     */
    public function executeS3Command(CommandInterface $command): Result
    {
        $presignedRequest = $this->client->createPresignedRequest($command, '+60 minutes');

        $guzzle = new GuzzleClient();
        $response = $guzzle->send($presignedRequest);

        $body = (string) $response->getBody();
        $commandName = $command->getName();

        // S3's CompleteMultipartUpload can return HTTP 200 with an <Error> body
        if ($body !== '' && str_contains($body, '<Error>')) {
            throw new RuntimeException("S3 returned an error for $commandName: $body");
        }

        return new Result($this->parseS3Response($commandName, $body));
    }

    /**
     * Parse the XML response body based on the S3 command type.
     *
     * @return array<string, mixed>
     */
    private function parseS3Response(string $commandName, string $body): array
    {
        if ($body === '') {
            return [];
        }

        $xml = @simplexml_load_string($body);
        if ($xml === false) {
            throw new RuntimeException("Failed to parse S3 XML response for $commandName: $body");
        }

        return match ($commandName) {
            'CreateMultipartUpload' => $this->parseCreateMultipartUpload($xml),
            'ListParts' => $this->parseListParts($xml),
            'CompleteMultipartUpload' => [],
            default => [],
        };
    }

    /**
     * @return array{UploadId: string}
     */
    private function parseCreateMultipartUpload(SimpleXMLElement $xml): array
    {
        return [
            'UploadId' => (string) $xml->UploadId,
        ];
    }

    /**
     * @return array{Parts: array<int, array{ETag: string, PartNumber: int}>}
     */
    private function parseListParts(SimpleXMLElement $xml): array
    {
        $parts = [];

        foreach ($xml->Part as $part) {
            $parts[] = [
                'ETag' => (string) $part->ETag,
                'PartNumber' => (int) $part->PartNumber,
            ];
        }

        return ['Parts' => $parts];
    }
}
