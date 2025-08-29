<?php

namespace App\MercureEvent\Game;

use App\MercureEvent\AbstractHubEventHandler;

class UpdateGame extends AbstractHubEventHandler
{
    public const DATA = [
            'frames' => [
                'GameContent',
                'PlayerList',
            ],
        ];

    public function __invoke(string $id = ''): void {
        $this->publish('GameUpdated' . $id, $this->toJson(self::DATA));
    }
}
