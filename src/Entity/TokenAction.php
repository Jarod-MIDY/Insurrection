<?php

namespace App\Entity;

use App\Enum\RolesActionsObtain;
use App\Enum\RolesActionsSuffer;
use App\Repository\TokenActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenActionRepository::class)]
class TokenAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tokenActions')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'tokenActions')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Scene $scene = null;

    #[ORM\Column(nullable: true, enumType: RolesActionsObtain::class)]
    private null|RolesActionsObtain $actionObtain = null;

    #[ORM\Column(nullable: true, enumType: RolesActionsSuffer::class)]
    private null|RolesActionsSuffer $actionSuffer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private null|InfluenceToken $influenceToken = null;

    public function getId(): null|int
    {
        return $this->id;
    }

    public function getPlayer(): null|Player
    {
        return $this->player;
    }

    public function setPlayer(null|Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getScene(): null|Scene
    {
        return $this->scene;
    }

    public function setScene(null|Scene $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    public function getActionObtain(): null|RolesActionsObtain
    {
        return $this->actionObtain;
    }

    public function setActionObtain(null|RolesActionsObtain $actionObtain): static
    {
        $this->actionObtain = $actionObtain;

        return $this;
    }

    public function getActionSuffer(): null|RolesActionsSuffer
    {
        return $this->actionSuffer;
    }

    public function setActionSuffer(null|RolesActionsSuffer $actionSuffer): static
    {
        $this->actionSuffer = $actionSuffer;

        return $this;
    }

    /**
     * Get the value of influenceToken.
     */
    public function getInfluenceToken(): null|InfluenceToken
    {
        return $this->influenceToken;
    }

    /**
     * Set the value of influenceToken.
     */
    public function setInfluenceToken(null|InfluenceToken $influenceToken): self
    {
        $this->influenceToken = $influenceToken;

        return $this;
    }
}
