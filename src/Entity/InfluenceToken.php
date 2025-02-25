<?php

namespace App\Entity;

use App\Enum\GameRoles;
use App\Repository\InfluenceTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfluenceTokenRepository::class)]
class InfluenceToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: GameRoles::class)]
    private ?GameRoles $linkedRole = null;

    #[ORM\ManyToOne(inversedBy: 'influenceTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkedRole(): ?GameRoles
    {
        return $this->linkedRole;
    }

    public function setLinkedRole(GameRoles $linkedRole): static
    {
        $this->linkedRole = $linkedRole;

        return $this;
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
}
