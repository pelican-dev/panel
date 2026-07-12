<?php

namespace App\Data;

use Spatie\LaravelData\PaginatedDataCollection as SpatiePaginatedDataCollection;

class PaginatedDataCollection extends SpatiePaginatedDataCollection
{
    protected bool $isFractal = false;

    public function setFractal(bool $value = true): static
    {
        $this->isFractal = $value;
        return $this;
    }

    protected array $_additional = [];

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
            
            $pagination = [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
                'links' => [],
            ];
            if ($paginator->nextPageUrl()) {
                $pagination['links']['next'] = $paginator->nextPageUrl();
            }
            if ($paginator->previousPageUrl()) {
                $pagination['links']['previous'] = $paginator->previousPageUrl();
            }

            $meta = $array['meta'] ?? [];
            unset(
                $meta['current_page'], 
                $meta['first_page_url'], 
                $meta['from'], 
                $meta['last_page'], 
                $meta['last_page_url'], 
                $meta['next_page_url'], 
                $meta['path'], 
                $meta['per_page'], 
                $meta['prev_page_url'], 
                $meta['to'], 
                $meta['total']
            );
            
            // Merge custom additional meta
            if (isset($this->_additional['meta'])) {
                $meta = array_merge($meta, $this->_additional['meta']);
            }
            
            $meta['pagination'] = $pagination;

            return [
                'object' => 'list',
                'data' => $formattedData,
                'meta' => $meta,
            ];
        }
        return $array;
    }
}
