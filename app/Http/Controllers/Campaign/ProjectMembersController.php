<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Billing\Subscription\Services\SubscriptionService;
use Module\Campaign\Enums\ProjectMemberRole;
use Module\Campaign\Enums\ProjectPermission;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

final class ProjectMembersController
{
    /**
     * @throws ToastException
     */
    public function add(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
//            'role' => ['required', 'string', new Enum(ProjectMemberRole::class)],
            'comment' => ['nullable', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$campaign->userHasAccess($user, ProjectPermission::MANAGE_MEMBERS)) {
            throw new ToastException('Вы не можете добавлять пользователей в проект.');
        }

//        if (
//            $data['role'] === ProjectMemberRole::OWNER
//            && !$campaign->userHasAccess($user, ProjectPermission::MANAGE_OWNER_MEMBER)
//        ) {
//            throw new ToastException('Вы не можете добавлять пользователей с такой ролью.');
//        }

        $newMember = User::query()->where('email', $data['email'])->firstOrFail();

        if ($campaign->users->where('email', $newMember->email)->count()) {
            throw new ToastException('Такой пользователь уже имеет доступ к проекту.');
        }

        $campaign->users()->attach($newMember, [
            'role' => ProjectMemberRole::MEMBER,
            'comment' => $data['comment'],
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Пользователь $newMember->name успешно добавлен в проект.",
        ]);
    }

    /**
     * @throws ToastException
     */
    public function remove(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$campaign->userHasAccess($user, ProjectPermission::MANAGE_MEMBERS)) {
            throw new ToastException('Вы не можете удалять пользователей из проекта.');
        }

        if ($campaign->users->count() <= 1) {
            throw new ToastException('Нельзя удалять единственного участника проекта.');
        }

        /** @var User $member */
        $member = $campaign->users->where('email', $data['email'])->first();

        if ($member === null) {
            throw new ToastException('Пользователь с указанной почтой не имеет доступа к проекту.');
        }

//        if ($member->id === $user->id) {
//            throw new ToastException('Нельзя удалить себя из проекта.');
//        }

        if (
            $member->pivot->role === ProjectMemberRole::OWNER->value
//            && !$campaign->userHasAccess($user, ProjectPermission::MANAGE_OWNER_MEMBER)
        ) {
            throw new ToastException('Вы не можете удалять пользователей с такой ролью.');
        }

        $campaign->users()->detach($member);

        if ($campaign->activeSubscription?->user?->id === $member->id) {
            app(SubscriptionService::class)->end($campaign->activeSubscription);
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Пользователь ' . $member->name . ' успешно удалён из проекта.',
        ]);
    }
}
