<?php

namespace App\Data;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Spatie\LaravelData\Data as SpatieData;

abstract class Data extends SpatieData
{
    public static string $_collectionClass = DataCollection::class;

    public static string $_paginatedCollectionClass = PaginatedDataCollection::class;

    public static string $_cursorPaginatedCollectionClass = CursorPaginatedDataCollection::class;

    protected bool $isFractal = false;

    public function setFractal(bool $value = true): static
    {
        $this->isFractal = $value;

        return $this;
    }

    public static function collection(mixed $items): DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection
    {
        if ($items instanceof Paginator || $items instanceof AbstractPaginator) {
            /** @var PaginatedDataCollection $collection */
            $collection = static::collect(
                $items,
                static::$_paginatedCollectionClass
            );

            return $collection;
        }

        if ($items instanceof CursorPaginator || $items instanceof AbstractCursorPaginator) {
            /** @var CursorPaginatedDataCollection $collection */
            $collection = static::collect(
                $items,
                static::$_cursorPaginatedCollectionClass
            );

            return $collection;
        }

        /** @var DataCollection $collection */
        $collection = static::collect(
            $items,
            static::$_collectionClass
        );

        return $collection;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if ($this->isFractal) {
            $resourceKey = method_exists($this, 'getResourceName')
                ? $this->getResourceName()
                : strtolower(class_basename($this));

            $additional = $this->getAdditionalData();
            foreach ($additional as $key => $value) {
                unset($array[$key]);
            }

            $response = [
                'object' => $resourceKey,
                'attributes' => $array,
            ];

            if (isset($additional['meta'])) {
                $response['meta'] = $additional['meta'];
            }

            return $response;
        }

        return $array;
    }
}
