<?php

namespace App\Enum;

enum SubscriptionStatus: string {
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case CANCELLED = 'cancelled';
}
