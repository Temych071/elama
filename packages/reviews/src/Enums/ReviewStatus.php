<?php

namespace Reviews\Enums;

enum ReviewStatus: string
{
    case NOT_MODERATED = 'not-moderated';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
}
