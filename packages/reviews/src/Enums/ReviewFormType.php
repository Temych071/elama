<?php

namespace Reviews\Enums;

enum ReviewFormType: string
{
    case DEFAULT = 'default';
    case SIMPLE = 'simple';
    case FEEDBACK = 'feedback';
}
