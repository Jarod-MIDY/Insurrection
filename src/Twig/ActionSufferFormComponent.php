<?php

namespace App\Twig;

use App\Entity\Player;
use App\Entity\Scene;
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
    public null|TokenAction $initialFormData = null;

    #[LiveProp]
    public null|Player $player = null;

    public function __construct()
    {
        $this->initialFormData = new TokenAction();
    }

    public function mount(Player $player): void
    {
        $this->player = $player;
    }

    #[\Override]
    protected function instantiateForm(): FormInterface
    {
        if (null === $this->initialFormData) {
            $this->initialFormData = new TokenAction();
        }
        $this->initialFormData->setPlayer($this->player);

        return $this->createForm(ActionSufferFormType::class, $this->initialFormData);
    }

    /**
     * Undocumented function
     *
     * @param EntityManagerInterface $entityManager
     * @throws \Symfony\Component\Form\Exception\RuntimeException
     * @throws \LogicException
     * @return RedirectResponse
     */
    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): RedirectResponse
    {
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
        $tokenAction->setScene($currentScene instanceof Scene ? $currentScene : null);
        $player->addRadianceToken();
        $entityManager->persist($player);
        $entityManager->persist($tokenAction);
        $entityManager->flush();

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }
}
