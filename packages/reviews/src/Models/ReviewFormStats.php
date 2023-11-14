<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ReviewFormStats extends Model
{
    protected $table = 'review_form_stats';

    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime',
    ];

    public function reviewForm(): BelongsTo
    {
        return $this->belongsTo(ReviewForm::class, 'review_form_id');
    }
}
