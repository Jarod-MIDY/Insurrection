<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/show/{game}', name: 'app_game_show')]
class AccessGameController extends AbstractController
{
    public function __invoke(
        Game $game,
        PlayerRepository $playerRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()]);
        if (!$player) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('game/index.html.twig', [
            'game' => $game,
            'player' => $player,
        ]);
    }
}
