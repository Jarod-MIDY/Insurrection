<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use App\Traits\TraitTimestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character
{
    use TraitTimestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Player $owner = null;

    #[ORM\Column(length: 255)]
    private null|string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private null|string $features = null;

    /**
     * @var Collection<int, Scene>
     */
    #[ORM\ManyToMany(targetEntity: Scene::class, mappedBy: 'characters')]
    private Collection $scenes;

    #[ORM\Column]
    private bool $isDead = false;

    #[ORM\ManyToOne(inversedBy: 'deadCharacters')]
    private null|Scene $dyingScene = null;

    public function __construct()
    {
        $this->scenes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getName() . ' (' . (string) $this->getId() . ')';
    }

    public function getId(): null|int
    {
        return $this->id;
    }

    public function getOwner(): null|Player
    {
        return $this->owner;
    }

    public function setOwner(null|Player $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): null|string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFeatures(): null|string
    {
        return $this->features;
    }

    public function setFeatures(null|string $features): static
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @return Collection<int, Scene>
     */
    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addScene(Scene $scene): static
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
            $scene->addCharacter($this);
        }

        return $this;
    }

    public function removeScene(Scene $scene): static
    {
        if ($this->scenes->removeElement($scene)) {
            $scene->removeCharacter($this);
        }

        return $this;
    }

    public function isDead(): bool
    {
        return $this->isDead;
    }

    public function setIsDead(bool $isDead): static
    {
        $this->isDead = $isDead;

        return $this;
    }

    public function getDyingScene(): null|Scene
    {
        return $this->dyingScene;
    }

    public function setDyingScene(null|Scene $dyingScene): static
    {
        $this->dyingScene = $dyingScene;

        return $this;
    }
}
