<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Model;

final class AnswerTemplate extends Model
{
    protected $table = 'review_answer_templates';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'text',
    ];
}
