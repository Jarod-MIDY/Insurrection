<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/show/{game}', name: 'app_game_show')]
class AccessGameController extends AbstractController
{
    public function __invoke(
        Game $game,
        Request $request,
        PlayerRepository $playerRepository,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()]);
        if (!$player) {
            throw $this->createAccessDeniedException();
        }
        $view = 'GameContent' === $request->headers->get('Turbo-Frame') ? 'game/_game_content.html.twig' : 'game/index.html.twig';

        return $this->render($view, [
            'game' => $game,
            'player' => $player,
        ]);
    }
}
