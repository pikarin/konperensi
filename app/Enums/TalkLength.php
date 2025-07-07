<?php

namespace App\Enums;

enum TalkLength: string
{
    case LIGHTNING = 'Lightning - 15 Minutes';
    case NORMAL = 'Normal - 30 minutes';
    case KEYNOTE = 'Keynote';
}
