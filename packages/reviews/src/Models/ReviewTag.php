<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class ReviewTag extends Pivot
{
    protected $table = 'review_reviews_tags';

    public $timestamps = false;

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
