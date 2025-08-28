<?php

namespace App\Twig;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('NewGameButton')]
class NewGameButtonComponent
{
    public ?Game $unfinishedGame = null;

    public function __construct(
        private GameRepository $gameRepository,
    ) {
    }

    public function mount(?User $author = null): void
    {
        $this->unfinishedGame = $this->gameRepository->findUnfinishedOrNull($author);
    }
}
