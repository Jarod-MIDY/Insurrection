<?php

namespace App\Controller\Player;

use App\Entity\Player;
use App\Form\PlayerInfoFormType;
use App\Interface\CharacterSheet;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/player/{player}/edit', name: 'app_player_edit')]
class EditPlayerController extends AbstractController
{
    /**
     * Summary of __invoke
     * @param \App\Entity\Player $player
     * @param \App\Repository\PlayerRepository $playerRepository
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \LogicException
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(Player $player, PlayerRepository $playerRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        $role = $player->getRole();
        if (null === $role) {
            return $this->redirectToRoute('app_player_save_roles_preferences', [
                'player' => $player->getId(),
            ]);
        }

        /**
         * @var CharacterSheet $playerInfoDto
         */
        $playerInfoDto = $role->getCharacterSheet($player->getInformations());
        $form = $this->createForm(PlayerInfoFormType::class, $playerInfoDto, [
            'game' => $player->getGame(),
            'action' => $this->generateUrl('app_player_edit', [
                'player' => $player->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $player->setInformations($playerInfoDto->__serialize());
            $playerRepository->save($player, true);

            return $this->redirectToRoute('app_game_show', [
                'game' => $player->getGame()?->getId(),
            ]);
        }

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('player/form.html.twig', [
            'form' => $form,
            'player' => $player,
        ]);
    }
}
