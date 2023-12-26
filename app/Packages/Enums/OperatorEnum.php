<?php

declare(strict_types=1);

namespace App\Packages\Enums;

enum OperatorEnum: string
{
    case L = 'l'; // <
    case LE = 'le'; // <=
    case G = 'g'; // >
    case GE = 'ge'; // >=
    case E = 'e'; // ==
    case NE = 'ne'; // !=
}
