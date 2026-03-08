<?php

namespace App\MercureEvent\Game;

use App\MercureEvent\AbstractHubEventHandler;

class UpdateLoby extends AbstractHubEventHandler
{
    public const DATA = [
        'frames' => [
            'LobbyList',
        ],
    ];

    public function __invoke(): void
    {
        $this->publish('UpdateLoby', $this->toJson(self::DATA));
    }
}
