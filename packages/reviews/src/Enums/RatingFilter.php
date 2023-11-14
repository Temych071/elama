<?php

namespace Reviews\Enums;

enum RatingFilter: string
{
    case new = 'new';
    case bad_reviews = 'bad_reviews';
    case good_reviews = 'good_reviews';
}
