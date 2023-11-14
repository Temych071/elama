<?php

namespace Module\Notification\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramNotificationModel extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'code',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'date'
    ];

    protected $table = 'telegram_notification';

    public function codeIsExpired(): bool
    {
        return now()->isAfter($this->expired_at);
    }
}
