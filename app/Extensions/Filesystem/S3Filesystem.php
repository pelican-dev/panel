<?php

/* The MIT License (MIT)

 Pterodactyl®
 Copyright © Dane Everitt <dane@daneeveritt.com> and contributors

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE. */

namespace App\Extensions\Filesystem;

use Aws\S3\S3ClientInterface;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

class S3Filesystem extends AwsS3V3Adapter
{
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
}
