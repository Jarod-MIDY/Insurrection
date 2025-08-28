<?php

namespace App\Twig;

use App\Entity\Player;
use App\Entity\TokenAction;
use App\Form\ActionObtainFormType;
use App\Repository\InfluenceTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('ActionObtainForm')]
class ActionObtainFormComponent extends AbstractController
{
    use DefaultActionTrait;

    use ComponentWithFormTrait;

    #[LiveProp]
    public int $actionTokens = 0;

    #[LiveProp]
    public Player $player;

    #[LiveProp]
    public ?TokenAction $initialFormData = null;

    public function __construct()
    {
        $this->initialFormData = new TokenAction();
    }

    public function mount(Player $player): void
    {
        $this->player = $player;
        $this->actionTokens = $player->getRadianceToken() + $player->getInfluenceTokens()->count();
    }

    protected function instantiateForm(): FormInterface
    {
        if (null === $this->initialFormData) {
            $this->initialFormData = new TokenAction();
        }
        $this->initialFormData->setPlayer($this->player);

        return $this->createForm(ActionObtainFormType::class, $this->initialFormData, [
            'influence_tokens' => $this->player->getInfluenceTokens()->toArray(),
        ]);
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager,
        InfluenceTokenRepository $influenceTokenRepository,
    ): RedirectResponse {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        /** @var TokenAction $tokenAction */
        $tokenAction = $this->getForm()->getData();
        $player = $tokenAction->getPlayer();
        if (null === $player) {
            $this->addFlash('error', 'Le joueur est introuvable');

            return $this->redirectToRoute('app_home', []);
        }
        $game = $player->getGame();
        if (null === $game) {
            $this->addFlash('error', 'La partie actuelle ne semble pas exister');

            return $this->redirectToRoute('app_home', []);
        }
        $currentScene = $game->getCurrentScene();
        $actionRole = $tokenAction->getActionObtain()?->getRoleFromAction();
        if (null === $actionRole) {
            $this->addFlash('error', 'Le role de l\'action est introuvable');

            return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
        }
        // check if using influence token
        if ($actionRole !== $player->getRole()) {
            $influenceToken = $influenceTokenRepository->findUsableToken(
                $player,
                $actionRole,
            );
            if (null === $influenceToken) {
                $this->addFlash('error', 'Le jeton d\'influence utiliser ne semble pas exister');

                return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
            }
            $influenceToken->setIsUsed(true);
            $tokenAction->setInfluenceToken($influenceToken);
            $entityManager->persist($influenceToken);
        } else {
            $player->setRadianceToken($player->getRadianceToken() - 1);
        }

        $tokenAction->setScene($currentScene);
        $entityManager->persist($player);
        $entityManager->persist($tokenAction);
        $entityManager->flush();

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }
}
