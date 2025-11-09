<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait ResolvesRecordDate
{
    /**
     * @param  mixed|null  $record
     */
    protected function resolveRecordDate($record = null): ?string
    {
        $r = $record ?? ($this->record ?? null);

        if (is_scalar($r)) {
            return (string) $r;
        }

        if (is_array($r)) {
            return Arr::get($r, 'date') !== null ? (string) Arr::get($r, 'date') : null;
        }

        if (is_object($r)) {
            if (method_exists($r, 'getAttribute')) {
                $val = $r->getAttribute('date');
                if ($val !== null) {
                    return (string) $val;
                }
            }

            if (isset($r->date) || property_exists($r, 'date')) {
                return (string) $r->date;
            }

            if (method_exists($r, 'toArray')) {
                $arr = $r->toArray();

                return Arr::get($arr, 'date') !== null ? (string) Arr::get($arr, 'date') : null;
            }
        }

        return null;
    }
}
