<?php

namespace App\Twig;

use App\Entity\Game;
use App\Enum\GameState;
use App\Repository\GameRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('LobbyList')]
class LobbyListComponent
{
    use DefaultActionTrait;

    /**
     * @var Game[]
     */
    public array $games = [];

    public function __construct(
        private GameRepository $gameRepository,
    ) {
        $this->refresh();
    }

    #[LiveAction]
    public function refresh(): void
    {
        $this->games = $this->gameRepository->findBy(['state' => [GameState::LOBBY, GameState::PLAYING]]);
    }
}
