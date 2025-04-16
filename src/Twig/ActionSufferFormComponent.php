<?php

namespace App\Twig;

use App\Entity\Player;
use App\Entity\TokenAction;
use App\Form\ActionSufferFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('ActionSufferForm')]
class ActionSufferFormComponent extends AbstractController
{
    use DefaultActionTrait;

    use ComponentWithFormTrait;

    #[LiveProp]
    public ?TokenAction $initialFormData = null;

    #[LiveProp]
    public ?Player $player = null;

    public function __construct()
    {
        $this->initialFormData = new TokenAction();
    }

    public function mount(Player $player)
    {
        $this->player = $player;
    }

    protected function instantiateForm(): FormInterface
    {
        $this->initialFormData->setPlayer($this->player);

        return $this->createForm(ActionSufferFormType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): RedirectResponse
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        /** @var TokenAction $tokenAction */
        $tokenAction = $this->getForm()->getData();
        $player = $tokenAction->getPlayer();
        $game = $player->getGame();
        $currentScene = $game->getCurrentScene();
        $tokenAction->setScene($currentScene);
        $player->addRadianceToken();
        $entityManager->persist($player);
        $entityManager->persist($tokenAction);
        $entityManager->flush();

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }
}
