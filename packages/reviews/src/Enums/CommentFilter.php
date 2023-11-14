<?php

namespace Reviews\Enums;

enum CommentFilter: string
{
    case has_comments = 'has_comments';
    case no_comments = 'no_comments';
}
