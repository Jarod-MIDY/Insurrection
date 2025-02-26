<?php

namespace App\Twig;

use App\Enum\GameState;
use App\Repository\GameRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('LobyList')]
class LobyListComponent
{
    public array $games = [];

    public function __construct(
        private GameRepository $gameRepository,
    ) {
        $this->games = $this->gameRepository->findBy(['state' => [GameState::LOBY, GameState::PLAYING]]);
    }
}
