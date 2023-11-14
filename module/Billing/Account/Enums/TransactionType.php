<?php

declare(strict_types=1);

namespace Module\Billing\Account\Enums;

enum TransactionType: string
{
    case REFILL_FROM_CARD = 'refill_from_card';
    case AUTO_REFILL = 'auto_refill';
    case SUBSCRIPTION_CHARGE = 'subscription_charge';
    case REFILL_BY_INVOICE = 'refill_by_invoice';
    case TRIAL_BALANCE = 'trial_balance';
    case DISCOUNT_CODE_AMOUNT = 'discount_code_amount';
    case DISCOUNT_CODE_PERCENT = 'discount_code_percent';
}
