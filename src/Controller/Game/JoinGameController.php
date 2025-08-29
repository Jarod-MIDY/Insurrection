<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Entity\Player;
use App\Form\JoinFormType;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/game/join/{game}', name: 'app_game_join')]
class JoinGameController extends AbstractController
{
    public function __invoke(
        Game $game, 
        Request $request,
        PlayerRepository $playerRepository,
        HubInterface $hub,
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($playerRepository->findOneBy(['game' => $game, 'linkedUser' => $this->getUser()])) {
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
            $playerRepository->save($player, true);
            $hub->publish(new Update(
                'UpdateLoby',
                '{}',
            ));

            return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->renderBlock('game/join.html.twig', 'password_form', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
