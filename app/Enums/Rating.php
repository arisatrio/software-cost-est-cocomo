<?php

namespace App\Enums;

enum Rating: string
{
    case VeryLow = 'VL';
    case Low = 'L';
    case Nominal = 'N';
    case High = 'H';
    case VeryHigh = 'VH';
    case ExtraHigh = 'XH';
}