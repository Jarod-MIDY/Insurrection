<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Scene;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/board/{game}', name: 'app_game_board')]
class ShowGameBoardController extends AbstractController
{
    public function __invoke(
        Game $game,
        SceneRepository $sceneRepository,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $game->getPlayers()->findFirst(fn (int $index, Player $player): bool => $player->getLinkedUser() === $this->getUser());
        if (!$player) {
            throw $this->createAccessDeniedException();
        }

        $lastScene = $game->getScenes()->last();
        if (!$lastScene instanceof Scene) {
            $lastScene = new Scene();
            $lastScene->setGame($game);
            $sceneRepository->save($lastScene, true);
        }

        $unUsedCharacters = array_diff(
            $player->getCharacters()->toArray(),
            $lastScene->getCharacters()->toArray()
        );

        return $this->render('game/board.html.twig', [
            'game' => $game,
            'player' => $player,
            'currentScene' => $lastScene,
            'unUsedCharacters' => $unUsedCharacters,
        ]);
    }
}
