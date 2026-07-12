<?php

namespace App\Data;

use Spatie\LaravelData\CursorPaginatedDataCollection as SpatieCursorPaginatedDataCollection;

class CursorPaginatedDataCollection extends SpatieCursorPaginatedDataCollection
{
    protected bool $isFractal = false;

    public function setFractal(bool $value = true): static
    {
        $this->isFractal = $value;

        return $this;
    }

    /** @var array<string, mixed> */
    protected array $_additional = [];

    /**
     * @param  array<string, mixed>  $additional
     */
    public function additional(array $additional): static
    {
        $this->_additional = array_merge($this->_additional, $additional);

        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if ($this->isFractal) {
            $resourceKey = method_exists($this->dataClass, 'getResourceNameStatic')
                ? ($this->dataClass)::getResourceNameStatic()
                : strtolower(class_basename($this->dataClass));

            $formattedData = [];
            $dataItems = $array['data'] ?? [];
            foreach ($dataItems as $item) {
                if (is_array($item)) {
                    unset($item['_additional']);
                }
                $formattedData[] = [
                    'object' => $resourceKey,
                    'attributes' => $item,
                ];
            }

            $paginator = $this->items;
            $cursor = [
                'current' => $paginator->cursor() ? $paginator->cursor()->encode() : null,
                'prev' => $paginator->previousCursor() ? $paginator->previousCursor()->encode() : null,
                'next' => $paginator->nextCursor() ? $paginator->nextCursor()->encode() : null,
                'count' => $paginator->count(),
            ];

            $meta = $array['meta'] ?? [];
            unset($meta['path'], $meta['per_page'], $meta['next_page_url'], $meta['prev_page_url']);

            if (isset($this->_additional['meta'])) {
                $meta = array_merge($meta, $this->_additional['meta']);
            }

            $meta['cursor'] = $cursor;

            return [
                'object' => 'list',
                'data' => $formattedData,
                'meta' => $meta,
            ];
        }

        return $array;
    }
}
