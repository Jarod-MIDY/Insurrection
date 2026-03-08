<?php

namespace App\Controller\Player;

use App\Entity\Player;
use App\Enum\GameRoles;
use App\Form\RolesSelectionFormType;
use App\MercureEvent\Game\UpdateGame;
use App\Records\RolesSelection;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player/{player}/save-roles-preferences', name: 'app_player_save_roles_preferences')]
class ChoosePreferedRolesController extends AbstractController
{
    /**
     * Summary of __invoke
     * @param \App\Entity\Player $player
     * @param \App\MercureEvent\Game\UpdateGame $updateGameSSE
     * @param \App\Repository\PlayerRepository $playerRepository
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \LogicException
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(
        Player $player,
        UpdateGame $updateGameSSE,
        PlayerRepository $playerRepository,
        Request $request,
    ): Response {
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
        if (!$form->isSubmitted() && (bool) $player->getPreferedRoles()) {
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
            $playerRepository->save($player, true);

            $updateGameSSE((string) $player->getGame()?->getId());
        }

        return $this->renderBlock('player/roles_selection.html.twig', 'waiting_for_other', [
            'player' => $player,
        ]);
    }
}
