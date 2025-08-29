<?php

namespace App\MercureEvent\Game;

use App\MercureEvent\AbstractHubEventHandler;

class UpdatePlayerList extends AbstractHubEventHandler
{
    public const DATA = [
        'frames' => [
            'PlayerList',
        ],
    ];

    public function __invoke(string $id = ''): void
    {
        $this->publish('GameUpdated'.$id, $this->toJson(self::DATA));
    }
}
