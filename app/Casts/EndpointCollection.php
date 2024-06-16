<?php

namespace App\Casts;

use App\Models\Objects\Endpoint;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class EndpointCollection implements Castable
{
    public static function castUsing(array $arguments)
    {
        return new class() implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                if (!isset($attributes[$key])) {
                    return new Collection();
                }

                $data = json_decode($attributes[$key], true);

                return (new Collection($data))->map(function ($value) {
                    return new Endpoint($value);
                });
            }

            public function set($model, $key, $value, $attributes)
            {
                if (!is_array($value) && !$value instanceof Collection) {
                    return new Collection();
                }

                if (!$value instanceof Collection) {
                    $value = new Collection($value);
                }

                return [
                    'ports' => $value->toJson(),
                ];
            }
        };
    }
}
