<?php

namespace App\Extensions\League\Fractal\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class PanelSerializer extends ArraySerializer
{
    /**
     * Serialize an item.
     *
     * @param mixed[] $data
     * @return array{object: ?string, attributes: mixed[]}
     */
    public function item(?string $resourceKey, array $data): array
    {
        return [
            'object' => $resourceKey,
            'attributes' => $data,
        ];
    }

    /**
     * Serialize a collection.
     *
     * @param mixed[] $data
     * @return array{object: 'list', data: mixed[]}
     */
    public function collection(?string $resourceKey, array $data): array
    {
        $response = [];
        foreach ($data as $datum) {
            $response[] = $this->item($resourceKey, $datum);
        }

        return [
            'object' => 'list',
            'data' => $response,
        ];
    }

    /**
     * Serialize a null resource.
     *
     * @return ?array{object: ?string, attributes: null}
     */
    public function null(): ?array
    {
        return [
            'object' => 'null_resource',
            'attributes' => null,
        ];
    }

    /**
     * Merge the included resources with the parent resource being serialized.
     *
     * @param array{relationships: array{string, mixed}} $transformedData
     * @param array{string, mixed} $includedData
     * @return array{relationships: array{string, mixed}}
     */
    public function mergeIncludes(array $transformedData, array $includedData): array
    {
        foreach ($includedData as $key => $datum) {
            $transformedData['relationships'][$key] = $datum;
        }

        return $transformedData;
    }
}
