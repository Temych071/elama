<?php

declare(strict_types=1);

namespace SocialWidget\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SocialWidgetStats extends Model
{
    protected $table = 'sw_stats';

    protected $fillable = [
        'views',
        'clicks',
    ];

    protected $dateFormat = 'Y-m-d';

    protected $casts = [
        'date' => 'datetime',
    ];

    public function widget(): BelongsTo
    {
        return $this->belongsTo(SocialWidget::class, 'widget_id');
    }
}
