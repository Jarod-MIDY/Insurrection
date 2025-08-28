<?php

namespace App\Twig;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PlayerReady')]
class PlayerReadyComponent
{
    use DefaultActionTrait;

    use ComponentToolsTrait;

    #[LiveProp(writable: false)]
    public int $playerId;

    #[LiveProp(writable: true)]
    public bool $readyToPlay;

    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    public function mount(Player $player): void
    {
        $this->playerId = $player->getId() ?? 0;
        $this->readyToPlay = $player->isReadyToPlay();
    }

    #[LiveAction()]
    public function changeReadyStatus(): void
    {
        $player = $this->playerRepository->find($this->playerId);
        if (null === $player || null === $player->getRole()) {
            return;
        }
        $characterSheet = $player->getRole()->getCharacterSheet($player->getInformations());
        if (!$characterSheet->isReady()) {
            $this->dispatchBrowserEvent('characterSheetNotReady');

            return;
        }
        $this->readyToPlay = !$this->readyToPlay;
        $player->setReadyToPlay($this->readyToPlay);
        $this->playerRepository->save($player, true);
    }
}
