<?php

namespace App\Enum;

enum GameState: string
{
    case PLANNED = 'planned';

    case PLAYING = 'playing';

    case CLOSED = 'closed';
}
