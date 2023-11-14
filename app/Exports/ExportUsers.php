<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Module\Exports\CreateElamaUsersTableAction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportUsers
{
    public function __construct(private readonly Collection $users)
    {
    }

    public function create(): Spreadsheet
    {
        $spreadSheet = new Spreadsheet();
        $workSheet = $spreadSheet->getActiveSheet();
 

        app(CreateElamaUsersTableAction::class)->handle($this->users, $workSheet);
        
        $this->columnsAutoSize($workSheet);
        $workSheet->setTitle('Список пользователей');

        return $spreadSheet;
    }

    public function columnsAutoSize(Worksheet $workSheet): void
    {
        $workSheet->getColumnDimensionByColumn(1)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(2)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(3)->setAutoSize(true);
        
    }
}
