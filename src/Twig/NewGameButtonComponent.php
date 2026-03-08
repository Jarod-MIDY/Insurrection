<?php

namespace App\Twig;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('NewGameButton')]
class NewGameButtonComponent
{
    public null|Game $unfinishedGame = null;

    public function __construct(
        private GameRepository $gameRepository,
    ) {}

    /**
     * Summary of mount
     * @param null|\App\Entity\User $author
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return void
     */
    public function mount(null|User $author = null): void
    {
        $this->unfinishedGame = $this->gameRepository->findUnfinishedOrNull($author);
    }
}
