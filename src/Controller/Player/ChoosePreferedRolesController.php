<?php

namespace App\Controller\Player;

use App\Entity\Player;
use App\Enum\GameRoles;
use App\Form\RolesSelectionFormType;
use App\Records\RolesSelection;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player/{player}/save-roles-preferences', name: 'app_player_save_roles_preferences')]
class ChoosePreferedRolesController extends AbstractController
{
        public function __invoke(
            Player $player, 
            HubInterface $hub,
            PlayerRepository $playerRepository,
            Request $request
        ): Response
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
            $playerRepository->save($player, true);
            $hub->publish(new Update(
                'GameUpdated' . $player->getGame()?->getId(),
                '{}',
            ));
        }

        return $this->renderBlock('player/roles_selection.html.twig', 'waiting_for_other', [
            'player' => $player,
        ]);
    }
}
