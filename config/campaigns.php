<?php

use Module\Campaign\Enums\ProjectMemberRole;
use Module\Campaign\Enums\ProjectPermission;
use Module\User\Enums\UserTariff;

return [
    'limits' => [
        UserTariff::free->value => 5,
        UserTariff::unlimited->value => 100,
        UserTariff::test->value => 100,
    ],
    'permissions' => [
        ProjectMemberRole::OWNER->value => [
            ProjectPermission::VIEW,
            ProjectPermission::MANAGE_MEMBERS,
            ProjectPermission::MANAGE_OWNER_MEMBER,
            ProjectPermission::PROJECT_SOURCES,
            ProjectPermission::PROJECT_SETTINGS,
            ProjectPermission::PROJECT_DELETE,
            ProjectPermission::PROJECT_BILLING,
        ],
        ProjectMemberRole::MEMBER->value => [
            ProjectPermission::VIEW,
//            ProjectPermission::MANAGE_MEMBERS,
            ProjectPermission::PROJECT_SOURCES,
            ProjectPermission::PROJECT_SETTINGS,
        ],
    ],
];
