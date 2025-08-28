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
    public ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tokenActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'tokenActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Scene $scene = null;

    #[ORM\Column(nullable: true, enumType: RolesActionsObtain::class)]
    private ?RolesActionsObtain $actionObtain = null;

    #[ORM\Column(nullable: true, enumType: RolesActionsSuffer::class)]
    private ?RolesActionsSuffer $actionSuffer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?InfluenceToken $influenceToken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }

    public function setScene(?Scene $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    public function getActionObtain(): ?RolesActionsObtain
    {
        return $this->actionObtain;
    }

    public function setActionObtain(?RolesActionsObtain $actionObtain): static
    {
        $this->actionObtain = $actionObtain;

        return $this;
    }

    public function getActionSuffer(): ?RolesActionsSuffer
    {
        return $this->actionSuffer;
    }

    public function setActionSuffer(?RolesActionsSuffer $actionSuffer): static
    {
        $this->actionSuffer = $actionSuffer;

        return $this;
    }

    /**
     * Get the value of influenceToken.
     */
    public function getInfluenceToken(): ?InfluenceToken
    {
        return $this->influenceToken;
    }

    /**
     * Set the value of influenceToken.
     */
    public function setInfluenceToken(?InfluenceToken $influenceToken): self
    {
        $this->influenceToken = $influenceToken;

        return $this;
    }
}
