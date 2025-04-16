<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Scene;
use App\Enum\GameState;
use App\Form\GameFormType;
use App\Form\JoinFormType;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class GameController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository,
        private PlayerRepository $playerRepository,
        private SceneRepository $sceneRepository,
    ) {
    }

    #[Route('/game/new', name: 'app_game_new')]
    public function newGame(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $game = new Game();
        $game->setAuthor($this->getUser());
        $game->setState(GameState::PLANNED);
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameRepository->save($game, true);

            return $this->redirectToRoute('app_game_edit', ['game' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/game/edit/{game}', name: 'app_game_edit')]
    public function editGame(Request $request, Game $game): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameRepository->save($game, true);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/game/create-lobby/{game}', name: 'app_game_lobby')]
    public function createJoinableLobby(Game $game): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $game->setState(GameState::LOBBY);
        $this->gameRepository->save($game, true);
        $player = new Player();
        $player->setGame($game);
        $player->setLinkedUser($this->getUser());
        $this->playerRepository->save($player, true);

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }

    #[Route('/game/close/{game}', name: 'app_game_close')]
    public function closeGame(Game $game): Response
    {
        $game->setState(GameState::CLOSED);
        $this->gameRepository->save($game, true);

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }

    #[Route('/game/show/{game}', name: 'app_game_show')]
    public function game(Game $game): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $this->playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()]);
        if (!$player) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('game/index.html.twig', [
            'game' => $game,
            'player' => $player,
        ]);
    }

    #[Route('/game/join/{game}', name: 'app_game_join')]
    public function joinGame(Game $game, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()])) {
            return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }
        if ($game->getPlayers()->count() >= 8) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->renderBlock('game/join.html.twig', 'max_players', [
                'game' => $game,
            ]);
        }
        $form = $this->createForm(JoinFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->get('password')->getData() === $game->getPassword()) {
            $player = new Player();
            $player->setGame($game);
            $player->setLinkedUser($this->getUser());
            $this->playerRepository->save($player, true);

            return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->renderBlock('game/join.html.twig', 'password_form', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/game/list-players/{game}', name: 'app_game_list_players')]
    public function listPlayers(Game $game, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if (GameState::PLAYING !== $game->getState()) {
            $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }

        return $this->render('game/list_players.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/game/board/{game}', name: 'app_game_board')]
    public function showGameBoard(Game $game): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $player = $game->getPlayers()->findFirst(fn (int $index, Player $player): bool => $player->getLinkedUser() === $this->getUser());
        if (!$player) {
            throw $this->createAccessDeniedException();
        }

        $lastScene = $game->getScenes()->last();
        if (!$lastScene instanceof Scene) {
            $lastScene = new Scene();
            $lastScene->setGame($game);
            $this->sceneRepository->save($lastScene, true);
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
