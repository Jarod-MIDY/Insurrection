<?php

namespace App\Entity;

use App\Repository\SceneStoryRepository;
use App\Traits\TraitTimestampable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceneStoryRepository::class)]
class SceneStory
{
    use TraitTimestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stories')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Scene $scene = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $player = null;

    #[ORM\Column(nullable: false)]
    private null|string $body = null;

    public function getId(): null|int
    {
        return $this->id;
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

    public function getPlayer(): null|Player
    {
        return $this->player;
    }

    public function setPlayer(null|Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getBody(): null|string
    {
        return $this->body;
    }

    public function setBody(null|string $body): static
    {
        $this->body = $body;

        return $this;
    }
}
