<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

final class JobBatch extends Model
{
    protected $table = 'job_batches';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'options' => 'collection',
        'failed_jobs' => 'integer',
        'created_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function isFinishedAttribute(): Attribute
    {
        return Attribute::get(static fn ($value, $attributes): bool => !is_null($attributes['finished_at']));
    }
}
