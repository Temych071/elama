<?php

declare(strict_types=1);

namespace Module\LinkChecker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\User\Models\User;

final class SeoAuditHistory extends Model
{
    protected $table = 'seo_audit_histories';

    protected $fillable = [
        'user_id',
        'seo_audit_result_uuid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seo_audit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
