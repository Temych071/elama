<?php

namespace Module\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CreateSeoAuditTableAction
{
    protected int $currentRowIndex = 1;
    protected array $groupTechnicalCondition = [];
    protected array $groupLoadingSpeed = [];
    protected array $groupWebsiteOptimization = [];
    protected int $totalSucceededChecks = 0;
    protected int $totalChecks = 0;

    private const styleTitle = [
        'font' => [
            'bold' => true,
            'size' => 16,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
        ],
    ];

    private const styleText = [
        'font' => [
            'size' => 12,
            'color' => ['rgb' => '343434'],
            'weight' => 400,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
        ],
    ];

    private const styleTextFailed = [
        'font' => [
            'size' => 12,
            'color' => ['rgb' => '343434'],
            'weight' => 500,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'F2DCDB',
            ]
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFa6a6a6']],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
        ],
    ];

    private const styleTextSucceeded = [
        'font' => [
            'size' => 12,
            'color' => ['rgb' => '343434'],
            'weight' => 500,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'D8E4BC'
            ]
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFa6a6a6']
            ],
        ],
    ];

    private const styleLinkTitle = [
        'font' => [
            'size' => 14,
            'weight' => 700,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
        ],
    ];

    public function execute(Worksheet $worksheet, Collection $seoAudit): void
    {
        $this->createGroups($seoAudit);
        $this->countChecks();

        $this->buildLinkAndDateRow($seoAudit, $worksheet);
        $this->buildTotalScore($seoAudit, $worksheet);
        $this->buildPassedChecks($worksheet);
        $this->buildScores($seoAudit, $worksheet);
        $this->buildSection('Техническое состояние', $this->groupTechnicalCondition, $worksheet);
        $this->buildSection('Оптимизация сайта', $this->groupWebsiteOptimization, $worksheet);
        $this->buildSection('Скорость зарузки сайта', $this->groupLoadingSpeed, $worksheet);
    }

    private function buildSection(string $title, array $group, Worksheet $worksheet): void
    {
        $worksheet->setCellValueByColumnAndRow(1, $this->currentRowIndex, $title)
            ->getStyleByColumnAndRow(1, $this->currentRowIndex)
            ->applyFromArray(self::styleTitle);
        $this->currentRowIndex++;

        foreach ($group['failed'] as $item) {
            $this->fillRowByTwoColumn(
                collect([$item['title'], $item['description'] ?? '']),
                $worksheet,
                self::styleTextFailed
            );
        }
        foreach ($group['succeeded'] as $item) {
            $this->fillRowByTwoColumn(
                collect([$item['title'], $item['description'] ?? '']),
                $worksheet,
                self::styleTextSucceeded
            );
        }
    }

    private function buildScores(Collection $seoAudit, Worksheet $worksheet): void
    {
        $seoScore = ($seoAudit->get('seo_score') ?? 0) * 100;
        $bestPracticesScore = ($seoAudit->get('best_practices_score') ?? 0) * 100;
        $performanceScore = ($seoAudit->get('performance_score') ?? 0) * 100;

        $this->fillRowByTwoColumn(collect(['SEO: ', $seoScore]), $worksheet);
        $this->fillRowByTwoColumn(collect(['Производительность: ', $performanceScore]), $worksheet);
        $this->fillRowByTwoColumn(collect(['Лучшие практики: ', $bestPracticesScore]), $worksheet);
    }

    private function buildPassedChecks(Worksheet $worksheet): void
    {
        $first = 'Пройдено проверок';
        $second = $this->totalSucceededChecks . ' из ' . $this->totalChecks;

        $this->fillRowByTwoColumn(collect([$first, $second]), $worksheet);
    }

    private function countChecks(): void
    {
        $groups = [$this->groupTechnicalCondition, $this->groupLoadingSpeed, $this->groupWebsiteOptimization];
        foreach ($groups as $category) {
            $this->totalSucceededChecks += is_countable($category['succeeded']) ? count($category['succeeded']) : 0;
            $this->totalChecks += (is_countable($category['succeeded']) ? count($category['succeeded']) : 0) + (is_countable($category['failed']) ? count($category['failed']) : 0);
        }
    }

    private function createGroups(Collection $seoAudit): void
    {
        $simpleResult = $seoAudit->get('simple_result');
        $this->groupTechnicalCondition = $this->groupCategory($simpleResult['technical_condition']);
        $this->groupLoadingSpeed = $this->groupCategory($simpleResult['loading_speed']);
        $this->groupWebsiteOptimization = $this->groupCategory($simpleResult['website_optimization']);
    }

    /**
     * @return array{succeeded: mixed[], failed: mixed[]}
     */
    private function groupCategory(array $category): array
    {
        $succeeded = [];
        $failed = [];

        foreach ($category as $field) {
            $score = $field['score'];
            if (empty($score)) {
                $score = 0;
            }

            if (empty($field) || $score < 0.5) {
                $failed[] = $field;
                continue;
            }

            $succeeded[] = $field;
        }

        return [
            'succeeded' => $succeeded,
            'failed' => $failed
        ];
    }

    private function buildTotalScore(Collection $seoAudit, Worksheet $worksheet): void
    {
        $simpleResult = $seoAudit->get('simple_result');
        $totalScore = 0;
        $count = 0;
        foreach ($simpleResult as $category) {
            foreach ($category as $check) {
                $totalScore += $check['score'] ?? 1;
                $count++;
            }
        }
        $score = round(($totalScore / $count) * 100, 2);

        $first = 'Общая оценка';
        $second = $score . ' из 100';

        $this->fillRowByTwoColumn(collect([$first, $second]), $worksheet);
    }

    private function buildLinkAndDateRow(Collection $seoAudit, Worksheet $worksheet): void
    {
        $date = Carbon::make($seoAudit->get('data_updated_at', now()))
            ?->tz('Europe/Moscow')
            ->format('d.m.Y H:m');
        $data = collect([$seoAudit->get('link', ''), $date,]);
        $this->fillRowByTwoColumn($data, $worksheet, self::styleLinkTitle);
    }

    private function fillRowByTwoColumn(Collection $data, Worksheet $worksheet, array $style = self::styleText): void
    {
        $colIndex = 1;
        foreach ($data as $item) {
            if (is_array($item)) {
                if (is_array($item[0])) {
                    foreach ($item as $key => $value) {
                        $item[$key] = $value['type'] . ': ' . implode(PHP_EOL, $value['urls'] ?? []);
                    }
                }
                $item = implode(PHP_EOL, $item);
            }
            $cell = $worksheet->setCellValueByColumnAndRow($colIndex, $this->currentRowIndex, $item);
            $cell->getStyleByColumnAndRow($colIndex, $this->currentRowIndex)
                ->applyFromArray($style)
                ->getAlignment()
                ->setWrapText(true);

            $colIndex++;
        }
        $this->currentRowIndex++;
    }
}
