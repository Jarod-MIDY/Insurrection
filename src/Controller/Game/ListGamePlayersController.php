<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Enum\GameState;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/game/list-players/{game}', name: 'app_game_list_players')]
class ListGamePlayersController extends AbstractController
{
    public function __invoke(Game $game): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (GameState::PLAYING !== $game->getState()) {
            $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }

        return $this->render('game/list_players.html.twig', [
            'game' => $game,
        ]);
    }
}
