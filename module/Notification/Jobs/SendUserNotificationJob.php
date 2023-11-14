<?php

declare(strict_types=1);

namespace Module\Notification\Jobs;

use App\Notifications\UserNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Module\User\Models\User;

final class SendUserNotificationJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public int $userId,
        public UserNotification $notification,
    ) {
        $this->onQueue("notifications");
    }

    public function handle(): void
    {
        Notification::send(User::findOrFail($this->userId), $this->notification);
    }
}
