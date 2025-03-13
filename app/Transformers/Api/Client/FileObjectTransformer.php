<?php

namespace App\Transformers\Api\Client;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class FileObjectTransformer extends BaseClientTransformer
{
    /**
     * @param array{
     *     name: string,
     *     mode: string,
     *     mode_bits: mixed,
     *     size: int,
     *     file: bool,
     *     symlink: bool,
     *     mime: string,
     *     created: mixed,
     *     modified: mixed,
     * } $item
     */
    public function transform($item): array
    {
        return [
            'name' => Arr::get($item, 'name'),
            'mode' => Arr::get($item, 'mode'),
            'mode_bits' => Arr::get($item, 'mode_bits'),
            'size' => Arr::get($item, 'size'),
            'is_file' => Arr::get($item, 'file', true),
            'is_symlink' => Arr::get($item, 'symlink', false),
            'mimetype' => Arr::get($item, 'mime', 'application/octet-stream'),
            'created_at' => Carbon::parse(Arr::get($item, 'created', ''))->toAtomString(),
            'modified_at' => Carbon::parse(Arr::get($item, 'modified', ''))->toAtomString(),
        ];
    }

    public function getResourceName(): string
    {
        return 'file_object';
    }
}
