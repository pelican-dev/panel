<?php

namespace App\Casts;

use App\Models\Objects\Endpoint;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use Stringable;
use TypeError;

class EndpointCollection implements Castable
{
    public static function castUsing(array $arguments)
    {
        return new class() implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                if (! isset($attributes[$key])) {
                    return new Collection();
                }

                $data = json_decode($attributes[$key], true);

                return (new Collection($data))->map(function ($value) {
                    return new Endpoint($value);
                });
            }

            public function set($model, $key, $value, $attributes)
            {
                if (!$value instanceof Collection) {
                    return new Collection();
                }

                return $value->map(fn ($endpoint) => (string) $endpoint);
            }
        };
    }
}
