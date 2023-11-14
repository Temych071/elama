<?php

namespace Loyalty\Enums;

enum LoyaltyCardTransactionType: string
{
    case PURCHASE = 'purchase';
    case BONUSES = 'bonuses';
}
