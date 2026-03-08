<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Enum\GameState;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/list-players/{game}', name: 'app_game_list_players')]
class ListGamePlayersController extends AbstractController
{
    /**
     * Summary of __invoke
     * @param \App\Entity\Game $game
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @return Response
     */
    public function __invoke(Game $game, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (GameState::PLAYING !== $game->getState()) {
            $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }
        $view = 'PlayerList' === $request->headers->get('Turbo-Frame')
            ? 'player/_player_list.html.twig'
            : 'player/player_list.html.twig';

        return $this->render($view, [
            'game' => $game,
        ]);
    }
}
