<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Enum\GameState;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/game/new', name: 'app_game_new')]
class CreateGameController extends AbstractController
{
    /**
     * Summary of __invoke
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\GameRepository $gameRepository
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(
        Request $request, 
        GameRepository $gameRepository,
        PasswordHasherFactoryInterface $hasherFactory
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $game = new Game();
        $game->setAuthor($this->getUser());
        $game->setState(GameState::PLANNED);
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the game password before saving
            if ($game->getPassword()) {
                $game->setPassword($game->getPassword(), $hasherFactory);
            }
            $gameRepository->save($game, true);

            return $this->redirectToRoute('app_game_edit', ['game' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'form' => $form,
        ]);
    }
}
