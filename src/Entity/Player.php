<?php

namespace App\Entity;

use App\Enum\GameRoles;
use App\Interface\CharacterSheet;
use App\Repository\PlayerRepository;
use App\Traits\TraitTimestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    use TraitTimestampable;

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
    #[ORM\OneToMany(targetEntity: InfluenceToken::class, mappedBy: 'receiver', orphanRemoval: true)]
    private Collection $influenceTokens;

    /**
     * @var Collection<int, InfluenceToken>
     */
    #[ORM\OneToMany(targetEntity: InfluenceToken::class, mappedBy: 'sender', orphanRemoval: true)]
    #[Assert\Count(
        min: 0,
        max: 3,
    )]
    private Collection $givenInfluenceTokens;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true, enumType: GameRoles::class)]
    private ?array $preferedRoles = null;

    #[ORM\Column]
    private bool $readyToPlay = false;

    /**
     * @var Collection<int, TokenAction>
     */
    #[ORM\OneToMany(targetEntity: TokenAction::class, mappedBy: 'player')]
    private Collection $tokenActions;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->influenceTokens = new ArrayCollection();
        $this->tokenActions = new ArrayCollection();
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

    public function getFormatedInformations(): CharacterSheet
    {
        return $this->getRole()->getCharacterSheet($this->getInformations());
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

    /**
     * @return Collection<int, InfluenceToken>
     */
    public function getGivenInfluenceToken(): Collection
    {
        return $this->givenInfluenceTokens->filter(fn (InfluenceToken $token): bool => !$token->isIsUsed());
    }

    public function addGivenInfluenceToken(InfluenceToken $token): static
    {
        if (!$this->givenInfluenceTokens->contains($token)) {
            $this->givenInfluenceTokens->add($token);
            $token->setSender($this);
        }

        return $this;
    }

    public function removeGivenInfluenceToken(InfluenceToken $token): static
    {
        if ($this->givenInfluenceTokens->removeElement($token)) {
            // set the owning side to null (unless already changed)
            if ($token->getSender() === $this) {
                $token->setSender(null);
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

    public function addRadianceToken(): void
    {
        ++$this->radianceToken;
    }

    /**
     * @return Collection<int, InfluenceToken>
     */
    public function getInfluenceTokens(): Collection
    {
        return $this->influenceTokens->filter(fn (InfluenceToken $token): bool => !$token->isIsUsed());
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

    public function isReadyToPlay(): bool
    {
        return $this->readyToPlay;
    }

    public function setReadyToPlay(bool $readyToPlay): static
    {
        $this->readyToPlay = $readyToPlay;

        return $this;
    }

    /**
     * @return Collection<int, TokenAction>
     */
    public function getTokenActions(): Collection
    {
        return $this->tokenActions;
    }

    public function addTokenAction(TokenAction $tokenAction): static
    {
        if (!$this->tokenActions->contains($tokenAction)) {
            $this->tokenActions->add($tokenAction);
            $tokenAction->setPlayer($this);
        }

        return $this;
    }

    public function removeTokenAction(TokenAction $tokenAction): static
    {
        if ($this->tokenActions->removeElement($tokenAction)) {
            // set the owning side to null (unless already changed)
            if ($tokenAction->getPlayer() === $this) {
                $tokenAction->setPlayer(null);
            }
        }

        return $this;
    }
}
