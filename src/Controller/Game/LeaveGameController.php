<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/leave/{game}', name: 'app_game_leave')]
class LeaveGameController extends AbstractController
{
    public function __invoke(
        Game $game,
        HubInterface $hub,
        PlayerRepository $playerRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()]);
        if (!$player instanceof Player || $player->getLinkedUser() === $game->getAuthor()) {
            return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }
        $playerRepository->remove($player, true);
        $hub->publish(new Update(
            'UpdateLoby',
            '{}',
        ));

        return $this->redirectToRoute('app_home');
    }
}
