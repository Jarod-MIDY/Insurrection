<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Entity\Player;
use App\Enum\GameState;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/create-lobby/{game}', name: 'app_game_lobby')]
class CreateLobyController extends AbstractController
{
    public function __invoke(
        Game $game,
        GameRepository $gameRepository,
        PlayerRepository $playerRepository,
        HubInterface $hub
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $game->setState(GameState::LOBBY);
        $gameRepository->save($game, true);
        $player = new Player();
        $player->setGame($game);
        $player->setLinkedUser($this->getUser());
        $playerRepository->save($player, true);
        $hub->publish(new Update(
            'UpdateLoby',
            '{}',
        ));

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }
}
