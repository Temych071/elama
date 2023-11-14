<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\User\Models\User;

final class ReviewAnswer extends Model
{
    protected $table = 'review_reviews_answers';

    public const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'text',
        'author_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
