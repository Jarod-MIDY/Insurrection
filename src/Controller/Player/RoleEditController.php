<?php

namespace App\Controller\Player;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player/{player}/role-edit', name: 'app_player_role_edit')]
class RoleEditController extends AbstractController
{
    /**
     * Summary of __invoke
     * @param \App\Entity\Player $player
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \LogicException
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(Player $player): Response
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
}
