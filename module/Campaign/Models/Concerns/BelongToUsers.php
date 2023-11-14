<?php

namespace Module\Campaign\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\User\Models\User;

trait BelongToUsers
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'comment']);
    }

    public function owner(): BelongsToMany
    {
        return $this->users()
            ->withPivot(['role', 'comment'])
            ->wherePivot('role', self::ROLE_OWNER);
    }

//    public static function bootBelongToUsers(): void
//    {
//        static::addGlobalScope(new CampaignAccessScope());
//    }
}
