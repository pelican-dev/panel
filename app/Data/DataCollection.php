<?php

namespace App\Data;

use Spatie\LaravelData\DataCollection as SpatieDataCollection;

class DataCollection extends SpatieDataCollection
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
            foreach ($array as $item) {
                // If the item itself has additional data merged, remove it from attributes
                if (is_array($item)) {
                    unset($item['_additional']);
                }
                $formattedData[] = [
                    'object' => $resourceKey,
                    'attributes' => $item,
                ];
            }

            $response = [
                'object' => 'list',
                'data' => $formattedData,
            ];

            if (isset($this->_additional['meta'])) {
                $response['meta'] = $this->_additional['meta'];
            }

            return $response;
        }
        return $array;
    }
}
