<?php

declare(strict_types=1);

namespace Module\Campaign\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class CampaignAccessScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereHas('users', function ($query): void {
            $query->where('users.id', auth()->id());
        });
    }

    public function extend(Builder $builder): void
    {
        $builder->macro('withoutCampaignUser', fn(Builder $builder): \Illuminate\Database\Eloquent\Builder => $builder->withoutGlobalScope($this));
    }
}
