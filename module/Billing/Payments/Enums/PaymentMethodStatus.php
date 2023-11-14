<?php

namespace Module\Billing\Payments\Enums;

enum PaymentMethodStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';
}
