<?php

namespace App\Enum;

enum GameState: string
{
    case PLANNED = 'planned';

    case LOBBY = 'lobby';

    case PLAYING = 'playing';

    case CLOSED = 'closed';
}
