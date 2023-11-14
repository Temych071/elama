<?php

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\User\Models\User;

final class ReviewComment extends Model
{
    use HasFactory;

    protected $table = 'review_comments';

    protected $fillable = [
        'text',
        'user_id',
        'review_id'
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
