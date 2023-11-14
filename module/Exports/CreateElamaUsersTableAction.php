<?php

namespace Module\Exports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
 
class CreateElamaUsersTableAction
{
    private const HEADERS = ['id', 'ИМЯ'];
    private int $currentRow = 2;

    public function handle(Collection $users, Worksheet $sheet): void
    {
        $this->createHeader($sheet);
        $this->fillUsers($sheet, $users);
    }

    private function fillUsers(Worksheet $sheet, Collection $users): void
    {

        
        $users->each(function ($user) use ($sheet): void {
            $formatData = [
                $user->id,
                $user->name,
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
