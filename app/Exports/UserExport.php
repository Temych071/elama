<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Module\Exports\CreateUsersTableAction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport 
{
    public function __construct(private readonly Collection $users)
    { 
    }

    public function create(): Spreadsheet
    {
        $spreadSheet = new Spreadsheet();
        $workSheet = $spreadSheet->getActiveSheet();

        app(CreateUsersTableAction::class)->handle($this->users, $workSheet);

        $this->columnsAutoSize($workSheet);
        $workSheet->setTitle('Список пользователей');

        return $spreadSheet;
    }

    public function columnsAutoSize(Worksheet $workSheet): void
    {
        $workSheet->getColumnDimensionByColumn(1)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(2)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(3)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(4)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(5)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(6)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(7)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(8)->setAutoSize(true);
    }
}
