<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Entity\Player;
use App\Form\JoinFormType;
use App\MercureEvent\Game\UpdateLoby;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/game/join/{game}', name: 'app_game_join')]
class JoinGameController extends AbstractController
{
    /**
     * Summary of __invoke
     * @param \App\Entity\Game $game
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\PlayerRepository $playerRepository
     * @param \App\MercureEvent\Game\UpdateLoby $updateLobySSE
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \LogicException
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(
        Game $game,
        Request $request,
        PlayerRepository $playerRepository,
        UpdateLoby $updateLobySSE,
        PasswordHasherFactoryInterface $hasherFactory,
    ): Response|RedirectResponse {
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
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordData = $form->get('password')->getData();
            if ($game->isPasswordValid(is_string($passwordData) ? $passwordData : '', $hasherFactory)) {
                $player = new Player();
                $player->setGame($game);
                $player->setLinkedUser($this->getUser());
                $playerRepository->save($player, true);
                $updateLobySSE();

                return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
            }
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->renderBlock('game/join.html.twig', 'password_form', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
