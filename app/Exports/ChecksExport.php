<?php

namespace App\Exports;

use JetBrains\PhpStorm\NoReturn;
use Module\Exports\CreateChecksTableAction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class ChecksExport
{
    protected array $sheets;
    protected CreateChecksTableAction $action;

    #[NoReturn] public function __construct(protected array $sources)
    {
        $this->action = new CreateChecksTableAction();
        $this->sheets = $this->getSheets();
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \Exception
     */
    public function create(): Spreadsheet
    {
        $spreadSheet = new Spreadsheet();
        $spreadSheet->removeSheetByIndex(0);

        foreach ($this->sheets as $sheetData) {
            $sheet = $spreadSheet->createSheet();
            $title = $this->getNameSource($sheetData['name']);
            $sheet->setTitle($title);

            app(CreateChecksTableAction::class)->handle($sheetData, $sheet);
        }

        foreach ($spreadSheet->getWorksheetIterator() as $worksheet) {
            $spreadSheet->setActiveSheetIndex($spreadSheet->getIndex($worksheet));

            $sheet = $spreadSheet->getActiveSheet();
            $sheet->getColumnDimensionByColumn(3)->setAutoSize(true);
            $sheet->getColumnDimensionByColumn(4)->setAutoSize(true);
            $sheet->getColumnDimensionByColumn(5)->setAutoSize(true);
            $sheet->getColumnDimensionByColumn(6)->setAutoSize(true);
            $sheet->getColumnDimensionByColumn(7)->setAutoSize(true);
        }

        $spreadSheet->setActiveSheetIndex(0);

        return $spreadSheet;
    }

    private function getSheets(): array
    {
        $sheets = [];
        $names = array_keys($this->sources);
        $nameIndex = 0;

        foreach ($this->sources as $checks) {
            $sheets[] = ['name' => $names[$nameIndex], 'checks' => $checks];
            $nameIndex++;
        }

        return $sheets;
    }

    private function getNameSource(string $source): string
    {
        return match ($source) {
            'yandex-direct' => 'Яндекс Директ',
            'vk' => 'VK',
            default => 'Неизвестный источник',
        };
    }
}
