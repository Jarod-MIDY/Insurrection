<?php

namespace App\Entity;

use App\Enum\GameRoles;
use App\Repository\InfluenceTokenRepository;
use App\Traits\TraitTimestampable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfluenceTokenRepository::class)]
class InfluenceToken
{
    use TraitTimestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\ManyToOne(inversedBy: 'influenceTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $receiver = null;

    #[ORM\ManyToOne(inversedBy: 'givenInfluenceTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $sender = null;

    #[ORM\Column]
    private bool $isUsed = false;

    public function getId(): null|int
    {
        return $this->id;
    }

    public function getLinkedRole(): null|GameRoles
    {
        return $this->sender?->getRole();
    }

    public function getReceiver(): null|Player
    {
        return $this->receiver;
    }

    public function setReceiver(null|Player $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSender(): null|Player
    {
        return $this->sender;
    }

    public function setSender(null|Player $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get the value of isUsed.
     */
    public function isIsUsed(): bool
    {
        return $this->isUsed;
    }

    /**
     * Set the value of isUsed.
     */
    public function setIsUsed(bool $isUsed): self
    {
        $this->isUsed = $isUsed;

        return $this;
    }
}
