<?php

namespace App\Controller\Game;

use App\Enum\GameState;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/lobby', name: 'app_game_list_lobby')]
class ListLobbyController extends AbstractController
{
    public function __invoke(Request $request, GameRepository $gameRepository): Response
    {
        if ($request->headers->get('Turbo-Frame') === null) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('game/_lobby_list.html.twig', [
            'games' => $gameRepository->findBy(['state' => [GameState::LOBBY, GameState::PLAYING]]),
        ]);
    }
}
