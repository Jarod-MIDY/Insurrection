<?php

namespace App\Entity;

use App\Enum\GameRoles;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $linkedUser = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column(enumType: GameRoles::class, nullable: true)]
    private ?GameRoles $role = null;

    #[ORM\Column]
    private array $informations = [];

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $characters;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private int $radianceToken = 0;

    /**
     * @var Collection<int, InfluenceToken>
     */
    #[ORM\OneToMany(targetEntity: InfluenceToken::class, mappedBy: 'player', orphanRemoval: true)]
    private Collection $influenceTokens;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true, enumType: GameRoles::class)]
    private ?array $preferedRoles = null;

    #[ORM\Column]
    private ?bool $readyToPlay = null;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->influenceTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkedUser(): ?User
    {
        return $this->linkedUser;
    }

    public function setLinkedUser(?User $linkedUser): static
    {
        $this->linkedUser = $linkedUser;

        return $this;
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

    public function getRole(): ?GameRoles
    {
        return $this->role;
    }

    public function setRole(GameRoles $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getInformations(): array
    {
        return $this->informations;
    }

    public function setInformations(array $informations): static
    {
        $this->informations = $informations;

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
            $character->setOwner($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getOwner() === $this) {
                $character->setOwner(null);
            }
        }

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getRadianceToken(): ?int
    {
        return $this->radianceToken;
    }

    public function setRadianceToken(int $radianceToken): static
    {
        $this->radianceToken = $radianceToken;

        return $this;
    }

    /**
     * @return Collection<int, InfluenceToken>
     */
    public function getInfluenceTokens(): Collection
    {
        return $this->influenceTokens;
    }

    public function addInfluenceToken(InfluenceToken $influenceToken): static
    {
        if (!$this->influenceTokens->contains($influenceToken)) {
            $this->influenceTokens->add($influenceToken);
            $influenceToken->setPlayer($this);
        }

        return $this;
    }

    public function removeInfluenceToken(InfluenceToken $influenceToken): static
    {
        if ($this->influenceTokens->removeElement($influenceToken)) {
            // set the owning side to null (unless already changed)
            if ($influenceToken->getPlayer() === $this) {
                $influenceToken->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return GameRoles[]|null
     */
    public function getPreferedRoles(): ?array
    {
        return $this->preferedRoles;
    }

    public function setPreferedRoles(?array $preferedRoles): static
    {
        $this->preferedRoles = $preferedRoles;

        return $this;
    }

    public function isReadyToPlay(): ?bool
    {
        return $this->readyToPlay;
    }

    public function setReadyToPlay(bool $readyToPlay): static
    {
        $this->readyToPlay = $readyToPlay;

        return $this;
    }
}
