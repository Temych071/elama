<?php

namespace Module\Notification;

use App\Notifications\UserNotification;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Module\Campaign\Models\Campaign;
use Module\Notification\Enums\NotificationStatuses;
use Module\Notification\Enums\NotificationTypes;
use Module\Notification\Jobs\SendUserNotificationJob;
use Module\User\Models\User;

class NotificationService
{
    private readonly array $campaigns;

    public function __construct(private readonly User|BelongsTo $user)
    {
        $this->campaigns = $this->getCampaignsData($this->user);
    }

    public function sendSimpleNotification(
        NotificationTypes $type,
        NotificationStatuses $status, // Зочем?)
        string $title,
        string $description,
        bool $withoutTextHtml = false,
        bool $tgBoldTitle = false,
    ): void {
        // Плохой идеей было сгребать в кучу тг и почту...
        // Надо будет потом сделать нормальные классы для уведомлений
        // Где для каждого канала будет свой метод сборки сообщения
        $data = $this->constructMessage($type, $title, $description, null, null, null, $withoutTextHtml, $tgBoldTitle);
        Notification::send($this->user, new UserNotification($data));
    }

    public function sendSimpleNotificationAt(
        NotificationTypes $type,
        string $title,
        string $text,
        DateTimeInterface $at,
        bool $tgBoldTitle = false,
    ): void {
        // С нормальными уведомлениями такой костыль не будет нужен))
        dispatch((new SendUserNotificationJob($this->user->id, new UserNotification($this->constructMessage(
            type: $type,
            title: $title,
            text: $text,
            withoutTextHtml: true,
            tgBoldTitle: $tgBoldTitle,
        ))))->delay($at));
    }

    public function sendNotifications(
        NotificationTypes $type,
        ?array $links,
        ?string $title,
        ?string $text,
        ?string $textHTML,
        $campaignId = null,
    ): void {
        $campaign = [];
        if ($campaignId) {
            $campaign = $this->getCampaignById($campaignId);
        }
        $data = $this->constructMessage($type, $title, $text, $campaign['name'] ?? null, $links, $textHTML);

        Notification::send($this->user, new UserNotification($data));
    }

    /**
     * @return array{type: string, date: Carbon, campaignName: string|null, title: string|null, text: string|null, links: mixed[]|null, textHTML: string}
     */
    private function constructMessage(
        NotificationTypes $type,
        ?string $title,
        ?string $text,
        ?string $campaignName = null,
        ?array $links = null,
        ?string $textHTML = null,

        // Чтобы не ломать ничего лишнего
        // При переделке уведомлений надо будет сделать нормально
        bool $withoutTextHtml = false,
        bool $tgBoldTitle = false,
    ): array { // Почему не DTOшка?
        return [
            'type' => $type->value,
            'date' => now(),
            'campaignName' => $campaignName,
            'title' => $title,
            'text' => $text,
            'links' => $links,

            // Это точно так должно было быть?
            // Оно ломает проверку в TelegramNotificationService::sendData,
            // т.к. получается что оно всегда есть
            'textHTML' => $withoutTextHtml ? null : ('<b>'.$title.'</b>'.PHP_EOL.$textHTML),

            'tgBoldTitle' => $tgBoldTitle,
        ];
    }

    private function getCampaignById(int $id)
    {
        foreach ($this->campaigns as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }

        return null;
    }

    private function getCampaignsData(User|BelongsTo $user): array
    {
        return $user
            ->campaigns()
            ->with('sources')
            ->select(['campaigns.id', 'campaigns.name'])
            ->get()
            ->transform(fn(Campaign $campaign): array => [
                'id' => $campaign->id,
                'name' => $campaign->name,
            ])
            ->toArray();
    }
}
