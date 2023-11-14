<?php

namespace Module\Exports;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Spatie\LaravelData\DataCollection;

class CreateChecksTableAction
{
    private const startCell = 'B2';
    private array $lastFreeCellCoords = [2, 2];
    private const mainColl = 2;

    private const styleTitle = [
        'font' => [
            'bold' => true,
            'size' => 16,
        ],
        'fill' => [

        ]
    ];
    private const styleRuleTitle = [
        'font' => [
            'size' => 14,
            'weight' => 700,
        ],
    ];
    private const styleText = [
        'font' => [
            'size' => 12,
            'color' => ['rgb' => '343434'],
            'weight' => 400,
        ],
    ];
    private const styleHyperLink = [
        'font' => [
            'size' => 12,
            'bold' => true,
            'color' => ['rgb' => '3098DB'],
        ],
    ];

    public function handle(array $data, Worksheet $sheet): void
    {
        $checks = $this->checksToArray($data);
        foreach ($checks as $name => $value) {
            $this->fillChecksCategory($value, $name, $sheet);
        }
    }

    private function fillChecksCategory(?array $data, string $rawName, Worksheet $sheet): void
    {
        if (empty($data)) {
            return;
        }

        $this->fillCategoryName($rawName, $sheet);
        foreach ($data as $checkObject) {
            $rule = $checkObject['rule'];
            $this->fillCell($rule['title'], $sheet, style: self::styleRuleTitle);
            $this->fillCell($rule['desc'], $sheet, 2, style: self::styleText);

            if (!empty($checkObject['message'])) {
                $this->fillCell($checkObject['message'], $sheet, style: self::styleText);
            }

            $failed = $this->getFailedObjects($checkObject);
            if ($failed === []) {
                continue;
            }

            $this->fillFailedObject($failed, $sheet);

            $this->backToMainCol();
        }
    }

    private function fillFailedObject(array $failed, Worksheet $worksheet): void
    {
        $hyperLinkFunction = static fn ($link, $name): string =>
            '=HYPERLINK('. '"' . $link . '"'. ',' . '"'. stripslashes(str_replace('"', '\'', (string) $name)) . '"'. ')';

        foreach ($failed as $item) {
            $this->fillCell('Индекс: ', $worksheet, 1, 0, self::styleText);
            $this->fillCell($item['index'], $worksheet, 1, 0, self::styleText);

            $this->fillCell('Название: ', $worksheet, 1, 0, self::styleText);
            $this->fillCell(
                $hyperLinkFunction(
                    $item['url'],
                    $item['name'],
                ),
                $worksheet,
                1,
                0,
                self::styleHyperLink
            );

            if (!empty($item['campaign'])) {
                $this->fillCell('Кампания: ', $worksheet, 1, 0, self::styleText);
                $this->fillCell(
                    $hyperLinkFunction(
                        $item['campaign']['url'],
                        $item['campaign']['name'],
                    ),
                    $worksheet,
                    1,
                    0,
                    self::styleHyperLink
                );
            }

            if (!empty($item['status'])) {
                $this->fillCell('Статус: ', $worksheet, 1, 0, self::styleText);
                $this->fillCell(
                    $this->formatStatus($item['status']),
                    $worksheet,
                    1,
                    0,
                    self::styleText
                );
            }

            $this->backToMainCol();
        }
    }

    private function fillCategoryName(string $rawName, Worksheet $worksheet): void
    {
        $name = $this->getNameCategory($rawName);
        $this->fillCell($name, $worksheet, style: self::styleTitle);
    }

    private function fillCell($data, Worksheet $worksheet, int $step = 1, int $direction = 1, array $style = []): void
    {
        $cell = $this->getFreeCellAndStepNext($step, $direction);
        $worksheet->setCellValueByColumnAndRow($cell[0], $cell[1], $data)
            ->getStyleByColumnAndRow($cell[0], $cell[1])
            ->applyFromArray($style)
            ->getAlignment()
            ->setVertical(Alignment::HORIZONTAL_CENTER);
    }

    private function getFreeCellAndStepNext(int $count = 1, int $direction = 1): array
    {
        $freeCell = $this->lastFreeCellCoords;
        $this->lastFreeCellCoords[$direction] += $count;

        return $freeCell;
    }

    private function backToMainCol(): void
    {
        $this->lastFreeCellCoords[0] = self::mainColl;
        ++$this->lastFreeCellCoords[1];
    }

    private function getNameCategory(string $rawName): string
    {
        return match ($rawName) {
            'accounts' => 'Аккаунт',
            'campaigns' => 'Кампании',
            'adgroups' => 'Группы объявлений',
            'ads' => 'Объявления',
            default => 'Неизвестная категория',
        };
    }

    private function getFailedObjects(array $checkObject): array
    {
        return array_map(static fn($object): array => [
            "name" => $object['name'],
            "campaign" => $object['campaign'],
            "url" => $object['url'],
            "index" => $object['index'],
            "status" => $object['custom']['status'] ?? null
        ], $checkObject['failedObjects']);
    }

    private function checksToArray(array $data): array
    {
        return array_map(static fn(?DataCollection $groups): ?array => $groups?->toArray(), $data['checks']);
    }

    private function formatStatus(string $status)
    {
        return match ($status) {
            'off' => 'Остановлен',
            'on' => 'Активен',
            'rejected' => 'Отклонен',
        };
    }
}
