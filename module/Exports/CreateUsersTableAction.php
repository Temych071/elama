<?php

namespace Module\Exports;

use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CreateUsersTableAction
{
    private const HEADERS = ['id', 'ИМЯ', 'БАЛАНС', 'КОЛ-ВО ПРОЕКТОВ', 'EMAIL', 'ТЕЛЕФОН', 'ДАТА РЕГИСТРАЦИИ', 'ДАТА ПОСЛЕДНЕГО ВХОДА'];
    private int $currentRow = 2;

    public function handle(Collection $users, Worksheet $sheet): void
    {
        $this->createHeader($sheet);
        $this->fillUsers($sheet, $users);
    }

    private function fillUsers(Worksheet $sheet, Collection $users): void
    {
        $users->each(function ($user) use ($sheet): void {
            $balance = $user['transactions_sum_amount'] / 1000 . ' ₽' ?? '0 ₽';
            $formatData = [
               $user['id'],
               $user['name'],
               $balance,
               $user['campaigns_count'],
               $user['email'],
               $user['phone'],
               $user['created_at']->tz('Europe/Moscow'),
               $user['last_visit_date']?->tz('Europe/Moscow'),
            ];
            $sheet->fromArray($formatData, null, 'A'.$this->currentRow);
            $this->currentRow++;
        });
    }

    private function createHeader(Worksheet $sheet): void
    {
        $sheet->fromArray(self::HEADERS);
    }
}
