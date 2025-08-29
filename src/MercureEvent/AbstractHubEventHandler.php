<?php

namespace App\MercureEvent;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

abstract class AbstractHubEventHandler
{
    public function __construct(
        protected HubInterface $hub,
    ) {
    }

    /**
     * @param array<string|int, mixed> $data
     */
    public function toJson(array $data): string
    {
        return json_encode($data) ?: '{}';
    }

    protected function publish(string $topic, string $message): void
    {
        $this->hub->publish(new Update($topic, $message));
    }
}
