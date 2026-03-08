<?php

namespace App\Entity;

use App\Repository\SceneLeaderVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceneLeaderVoteRepository::class)]
class SceneLeaderVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'sceneLeaderVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Scene $scene = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $votedForPlayer = null;

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

    public function getVotedForPlayer(): null|Player
    {
        return $this->votedForPlayer;
    }

    public function setVotedForPlayer(null|Player $votedForPlayer): static
    {
        $this->votedForPlayer = $votedForPlayer;

        return $this;
    }
}
