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

    public function mount(Player $player)
    {
        $this->player = $player;
        $this->actionTokens = $player->getRadianceToken() + $player->getInfluenceTokens()->count();
    }

    protected function instantiateForm(): FormInterface
    {
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
        $game = $player->getGame();
        $currentScene = $game->getCurrentScene();

        // check if using influence token
        if ($tokenAction->getActionObtain()->getRoleFromAction() !== $player->getRole()) {
            $influenceToken = $influenceTokenRepository->findUsableToken(
                $player,
                $tokenAction->getActionObtain()->getRoleFromAction(),
            );
            if (null === $influenceToken) {
                $this->addFlash('error', 'Influence token not found');

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
