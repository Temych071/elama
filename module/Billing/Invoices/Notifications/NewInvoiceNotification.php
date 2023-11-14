<?php

declare(strict_types=1);

namespace Module\Billing\Invoices\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

final class NewInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @return array{mail: string}
     */
    #[ArrayShape(['mail' => "string"])]
    public function viaQueues(): array
    {
        return ['mail' => 'mail'];
    }

    private const MAIL_TITLE = 'Новый запрос счёта на оплату';

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(self::MAIL_TITLE)
            ->greeting(self::MAIL_TITLE)
            ->action('Посмотреть все запросы', route('admin.invoices.list'));
    }
}
