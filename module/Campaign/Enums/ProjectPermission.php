<?php

namespace Module\Campaign\Enums;

enum ProjectPermission: string
{
    case VIEW = 'view';

    case PROJECT_DELETE = 'project-delete';
    case PROJECT_SETTINGS = 'project-settings';
    case PROJECT_SOURCES = 'project-sources';
    case PROJECT_BILLING = 'project-billing';

    case MANAGE_MEMBERS = 'manage-members';
    case MANAGE_OWNER_MEMBER = 'manage-owner-member';
}
