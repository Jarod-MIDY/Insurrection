<?php

namespace App\Enum;

enum GameState: string
{
    case PLANNED = 'planned';

    case LOBY = 'loby';

    case PLAYING = 'playing';

    case CLOSED = 'closed';
}
