<?php

namespace App\Entity;

use App\Repository\SceneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceneRepository::class)]
class Scene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'scenes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'scenes')]
    private Collection $characters;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $estimatedDuration = null;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $realDuration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $goal = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $story = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'dyingScene')]
    private Collection $deadCharacters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->deadCharacters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        $this->characters->removeElement($character);

        return $this;
    }

    public function getEstimatedDuration(): ?\DateInterval
    {
        return $this->estimatedDuration;
    }

    public function setEstimatedDuration(?\DateInterval $estimatedDuration): static
    {
        $this->estimatedDuration = $estimatedDuration;

        return $this;
    }

    public function getRealDuration(): ?\DateInterval
    {
        return $this->realDuration;
    }

    public function setRealDuration(?\DateInterval $realDuration): static
    {
        $this->realDuration = $realDuration;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): static
    {
        $this->goal = $goal;

        return $this;
    }

    public function getStory(): ?string
    {
        return $this->story;
    }

    public function setStory(?string $story): static
    {
        $this->story = $story;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getDeadCharacters(): Collection
    {
        return $this->deadCharacters;
    }

    public function addDeadCharacter(Character $deadCharacter): static
    {
        if (!$this->deadCharacters->contains($deadCharacter)) {
            $this->deadCharacters->add($deadCharacter);
            $deadCharacter->setDyingScene($this);
        }

        return $this;
    }

    public function removeDeadCharacter(Character $deadCharacter): static
    {
        if ($this->deadCharacters->removeElement($deadCharacter)) {
            // set the owning side to null (unless already changed)
            if ($deadCharacter->getDyingScene() === $this) {
                $deadCharacter->setDyingScene(null);
            }
        }

        return $this;
    }
}
