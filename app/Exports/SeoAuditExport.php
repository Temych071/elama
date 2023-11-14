<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Module\Exports\CreateSeoAuditTableAction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SeoAuditExport
{
    public function __construct(protected Collection $seoAudit)
    {
    }

    public function create(): Spreadsheet
    {
        $spreadSheet = new Spreadsheet();
        $workSheet = $spreadSheet->getActiveSheet();

        app(CreateSeoAuditTableAction::class)->execute($workSheet, $this->seoAudit);

        $workSheet->getColumnDimensionByColumn(1)->setAutoSize(true);
        $workSheet->getColumnDimensionByColumn(2)->setAutoSize(true);

        return $spreadSheet;
    }
}
