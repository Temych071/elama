<?php

declare(strict_types=1);

namespace App\Http\Controllers\SeoAudits;

use App\Exports\SeoAuditExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Module\LinkChecker\Models\SeoAudit;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SeoAuditLinkCheckerController
{
    public function getLastAudit(Request $request)
    {
        $link = $request->get('link');

        return SeoAudit::findByLink($link)
            ?->whereRelation('history', 'user_id', '=', $request->user()->id)
            ?->select(['uuid', 'status']);
    }

    public function auditByUuid(Request $request, string $uuid)
    {
        return SeoAudit::query()
            ->whereRelation('history', 'user_id', '=', $request->user()->id)
            ->where('uuid', $uuid)
            ->first();
    }

    public function exportSeoAudit(Request $request, string $uuid): ?StreamedResponse
    {
        $seoAudit = SeoAudit::whereUuid($uuid)->get()->toArray();

        $seoAudit = collect(...$seoAudit);

        if ($seoAudit->get('status') !== 'success') {
            return null;
        }

        $export = new SeoAuditExport($seoAudit);
        $spreadSheet = $export->create();

        $filename = 'SEO проверка ' . Carbon::now()->tz('Europe/Moscow')->format('d.m.Y H-i') . '.xlsx';

        return response()->stream(function () use ($spreadSheet): void {
            (new Xlsx($spreadSheet))->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
        ])->send();
    }
}
