<?php

namespace App\Controller;

use App\Entity\Player;
use App\Enum\GameRoles;
use App\Form\PlayerInfoFormType;
use App\Form\RolesSelectionFormType;
use App\Records\RolesSelection;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class PlayerController extends AbstractController
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private HubInterface $hub,
    ) {
    }

    #[Route('/player/{player}/role-edit', name: 'app_player_role_edit')]
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
            'informations' => $player->getFormatedInformations(),
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
            return $this->redirectToRoute('app_player_role_edit', [
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
            $this->hub->publish(new Update(
                'GameUpdated' . $player->getGame()?->getId(),
                '{}',
            ));
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
        if ($player->getPreferedRoles() === [] || null === $player->getPreferedRoles()) {
            return $this->redirectToRoute('app_player_save_roles_preferences', [
                'player' => $player->getId(),
            ]);
        }
        $player->setPreferedRoles([]);
        $this->playerRepository->save($player, true);
        $this->hub->publish(new Update(
            'GameUpdated' . $player->getGame()?->getId(),
            '{}',
        ));

        return $this->redirectToRoute('app_player_save_roles_preferences', [
            'player' => $player->getId(),
        ]);
    }

    #[Route('/player/{player}/edit', name: 'app_player_edit')]
    public function edit(Player $player, Request $request): Response
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

        $playerInfoDto = $player->getRole()->getCharacterSheet($player->getInformations());
        $form = $this->createForm(PlayerInfoFormType::class, $playerInfoDto, [
            'game' => $player->getGame(),
            'action' => $this->generateUrl('app_player_edit', [
                'player' => $player->getId(),
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $player->setInformations($playerInfoDto->__serialize());
            $this->playerRepository->save($player, true);

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
