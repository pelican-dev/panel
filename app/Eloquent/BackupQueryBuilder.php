<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class BackupQueryBuilder extends Builder
{
    public function nonFailed(): self
    {
        $this->where(function (Builder $query) {
            $query
                ->whereNull('completed_at')
                ->orWhere('is_successful', true);
        });

        return $this;
    }
}
