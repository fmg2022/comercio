<?php

namespace App\Enums;

enum DeliveryMethod: string
{
    case Shipping = 'shipping';
    case Pickup = 'pickup';
}
