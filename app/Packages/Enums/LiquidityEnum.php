<?php

declare(strict_types=1);

namespace App\Packages\Enums;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: LiquidityEnum::A)]
enum LiquidityEnum: string
{
    case A = 'A';
    case D = 'D';
    case DR = 'DR';
    case F = 'F';
    case FS = 'FS';
    case GC = 'GC';
    case L = 'L';
    case L1 = 'L1';
    case L2 = 'L2';
    case L3 = 'L3';
    case L4 = 'L4';
    case L5 = 'L5';
    case NF = 'NF';
    case NR = 'NR';
    case NT = 'NT';
    case R = 'R';
    case RS = 'RS';
    case SS = 'SS';
    case SU = 'SU';
    case T = 'T';
    case TM = 'TM';
    case TS = 'TS';
    case U4 = 'U4';
    case W = 'W';
    case X = 'X';
    case DELIVERY = 'доставка';
}
