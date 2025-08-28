<?php

namespace App\Twig;

use App\Entity\InfluenceToken;
use App\Entity\Player;
use App\Form\SendInfluenceTokenFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('ActionSendInfluenceToken')]
class ActionSendInfluenceTokenComponent extends AbstractController
{
    use DefaultActionTrait;

    use ComponentWithFormTrait;

    #[LiveProp]
    public Player $sender;

    public function __construct(
        #[LiveProp]
        public InfluenceToken $initialFormData = new InfluenceToken(),
    ) {
    }

    public function mount(Player $sender): void
    {
        $this->sender = $sender;
    }

    public function instantiateForm(): FormInterface
    {
        $this->initialFormData->setSender($this->sender);

        return $this->createForm(SendInfluenceTokenFormType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): RedirectResponse
    {
        $this->submitForm();

        /** @var InfluenceToken $token */
        $token = $this->getForm()->getData();

        $entityManager->persist($token);
        $player = $token->getSender();
        if (null === $player) {
            $this->addFlash('error', 'Le joueur est introuvable');

            return $this->redirectToRoute('app_home', []);
        }
        $game = $player->getGame();
        if (null === $game) {
            $this->addFlash('error', 'La partie actuelle ne semble pas exister');

            return $this->redirectToRoute('app_home', []);
        }
        $player->addGivenInfluenceToken($token);
        $entityManager->persist($player);
        $entityManager->flush();

        return $this->redirectToRoute('app_game_show', ['game' => $game->getId()]);
    }
}
