<?php

namespace App\Controller;

use App\Entity\Player;
use App\Enum\GameRoles;
use App\Form\RolesSelectionFormType;
use App\Records\RolesSelection;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayerController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository,
        private PlayerRepository $playerRepository,
    ) {
    }

    #[Route('/player/{player}', name: 'app_player_show')]
    public function show(Player $player): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        if (null === $player->getRole()) {
            return $this->redirectToRoute('app_player_save_roles_preferences', [
                'player' => $player->getId(),
            ]);
        }

        return $this->render('player/index.html.twig', [
            'player' => $player,
        ]);
    }

    #[Route('/player/{player}/save-roles-preferences', name: 'app_player_save_roles_preferences')]
    public function setRolesPreferences(Player $player, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        if (null !== $player->getRole()) {
            return $this->redirectToRoute('app_player_show', [
                'player' => $player->getId(),
            ]);
        }
        $selectedRoles = new RolesSelection();
        $form = $this->createForm(RolesSelectionFormType::class, $selectedRoles, [
            'action' => $this->generateUrl('app_player_save_roles_preferences', [
                'player' => $player->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if (!$form->isSubmitted() && [] === $player->getPreferedRoles()) {
            return $this->renderBlock('player/roles_selection.html.twig', 'roles_form', [
                'form' => $form,
                'player' => $player,
            ]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ([] === $selectedRoles->getRoles()) {
                $selectedRoles->rightsOfWay = GameRoles::getRightsOfWay();
                $selectedRoles->trajectories = GameRoles::getTrajectories();
            }
            $player->setPreferedRoles($selectedRoles->getRoles());
            $this->playerRepository->save($player, true);
        }

        return $this->renderBlock('player/roles_selection.html.twig', 'waiting_for_other', [
            'player' => $player,
        ]);
    }

    #[Route('/player/{player}/clear-roles-preferences', name: 'app_player_clear_roles_preferences')]
    public function clearRolesPreferences(Player $player): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        $player->setPreferedRoles([]);
        $this->playerRepository->save($player, true);

        return $this->redirectToRoute('app_player_save_roles_preferences', [
            'player' => $player->getId(),
        ]);
    }

    #[Route('/player/{player}/leave', name: 'app_player_leave')]
    public function leaveGame(Player $player): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->getUser() !== $player->getLinkedUser()) {
            throw $this->createAccessDeniedException();
        }
        $this->playerRepository->remove($player, true);

        return $this->redirectToRoute('app_home');
    }
}
