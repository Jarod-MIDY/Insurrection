<?php

namespace App\Entity;

use App\Enum\GameRoles;
use App\Enum\GameState;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    private const RADIANCE_TOKENS_PER_PLAYER = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $thingsToTalkAbout = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $thingsToHalfTalk = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $banedTopics = null;

    /**
     * @var Collection<int, Player>
     */
    #[Assert\Count(min: 0, max: 8, )]
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'game', orphanRemoval: true)]
    private Collection $players;

    #[Assert\Range(
        min: 5,
        max: 8,
    )]
    #[ORM\Column]
    private int $maxPlayers = 8;

    /**
     * @var Collection<int, Scene>
     */
    #[ORM\OneToMany(targetEntity: Scene::class, mappedBy: 'game', orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $scenes;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(enumType: GameState::class)]
    private ?GameState $state = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->scenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getThingsToTalkAbout(): ?string
    {
        return $this->thingsToTalkAbout;
    }

    public function setThingsToTalkAbout(?string $thingsToTalkAbout): static
    {
        $this->thingsToTalkAbout = $thingsToTalkAbout;

        return $this;
    }

    public function getThingsToHalfTalk(): ?string
    {
        return $this->thingsToHalfTalk;
    }

    public function setThingsToHalfTalk(?string $thingsToHalfTalk): static
    {
        $this->thingsToHalfTalk = $thingsToHalfTalk;

        return $this;
    }

    public function getBanedTopics(): ?string
    {
        return $this->banedTopics;
    }

    public function setBanedTopics(?string $banedTopics): static
    {
        $this->banedTopics = $banedTopics;

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setGame($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getGame() === $this) {
                $player->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Scene>
     */
    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function getCurrentScene(): ?Scene
    {
        return $this->scenes->last();
    }

    public function addScene(Scene $scene): static
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
            $scene->setGame($this);
        }

        return $this;
    }

    public function removeScene(Scene $scene): static
    {
        if ($this->scenes->removeElement($scene)) {
            // set the owning side to null (unless already changed)
            if ($scene->getGame() === $this) {
                $scene->setGame(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getState(): ?GameState
    {
        return $this->state;
    }

    public function setState(GameState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(?string $type = null): array
    {
        if ('trajectories' === $type) {
            return GameRoles::getTrajectories();
        }
        if ('rightsOfWay' === $type) {
            return GameRoles::getRightsOfWay();
        }

        return GameRoles::cases();
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): static
    {
        $this->maxPlayers = $maxPlayers;

        return $this;
    }

    public function getPlayersWithPreferedRole(string|GameRoles $role): array
    {
        if (!$role instanceof GameRoles) {
            $role = GameRoles::from($role);
        }

        return $this->players->filter(function (Player $player) use ($role) {
            return in_array($role, $player->getPreferedRoles());
        })->toArray();
    }

    public function getRadianceTokenLimit(): int
    {
        return $this->maxPlayers * self::RADIANCE_TOKENS_PER_PLAYER;
    }

    public function getCurrentTotalRadianceTokens(): int
    {
        return $this->getPlayers()->reduce(function (int $carry, Player $player) {
            return $carry + $player->getRadianceToken();
        }, 0);
    }

    public function radianceLimitReached(): bool
    {
        return $this->getCurrentTotalRadianceTokens() >= $this->getRadianceTokenLimit();
    }
}
