<?php

namespace App\Http\Controllers\Admin\Users;

use App\Exports\UserExport;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Module\User\Models\User;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UsersListController
{
    public function __invoke(Request $request): Responsable
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'phone', 'role', 'tariff', 'last_visit_date', 'created_at'])
            ->withSum('transactions', 'amount')
            ->withCount('campaigns')
            ->latest();

        $hasActiveSubscriptionFilter = $request->query('has_active_subscriptions');

        if ($hasActiveSubscriptionFilter === 'active') {
            $users = $users->whereHas('activeSubscriptions');
        } elseif ($hasActiveSubscriptionFilter === 'inactive') {
            $users = $users->whereDoesntHave('activeSubscriptions');
        }
 
        return Inertia::render('Admin/Users/List', [
            'users' => $users->get(),
        ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'phone', 'role', 'tariff', 'last_visit_date', 'created_at'])
            ->withSum('transactions', 'amount')
            ->withCount('campaigns')
            ->latest()
            ->get();
        $userExport = new UserExport($users);
        $spreadSheet = $userExport->create();

        $filename = 'Список пользователей-' . Carbon::now()->format('d.m.Y') . '.xlsx';

        return response()->stream(function () use ($spreadSheet): void {
            (new Xlsx($spreadSheet))->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
        ])->send();
    }
}
