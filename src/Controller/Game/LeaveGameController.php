<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Entity\Player;
use App\MercureEvent\Game\UpdateLoby;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/leave/{game}', name: 'app_game_leave')]
class LeaveGameController extends AbstractController
{
    public function __invoke(
        Game $game,
        UpdateLoby $updateLobySSE,
        PlayerRepository $playerRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()]);
        if (!$player instanceof Player || $player->getLinkedUser() === $game->getAuthor()) {
            return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }
        $playerRepository->remove($player, true);
        $updateLobySSE();

        return $this->redirectToRoute('app_home');
    }
}
