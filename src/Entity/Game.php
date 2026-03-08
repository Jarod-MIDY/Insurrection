<?php

namespace App\Entity;

use App\Enum\GameRoles;
use App\Enum\GameState;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game implements PasswordAuthenticatedUserInterface
{
    private const RADIANCE_TOKENS_PER_PLAYER = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\Column(length: 255)]
    private null|string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private null|string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private null|string $thingsToTalkAbout = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private null|string $thingsToHalfTalk = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private null|string $banedTopics = null;

    /**
     * @var Collection<int, Player>
     */
    #[Assert\Count(min: 0, max: 8)]
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'game', orphanRemoval: true)]
    private Collection $players;

    #[Assert\Range(min: 5, max: 8)]
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
    private null|User $author = null;

    #[ORM\Column(enumType: GameState::class)]
    private null|GameState $state = null;

    #[ORM\Column(length: 255)]
    private null|string $password = null;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->scenes = new ArrayCollection();
    }

    public function getId(): null|int
    {
        return $this->id;
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

    public function getSubject(): null|string
    {
        return $this->subject;
    }

    public function setSubject(null|string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getThingsToTalkAbout(): null|string
    {
        return $this->thingsToTalkAbout;
    }

    public function setThingsToTalkAbout(null|string $thingsToTalkAbout): static
    {
        $this->thingsToTalkAbout = $thingsToTalkAbout;

        return $this;
    }

    public function getThingsToHalfTalk(): null|string
    {
        return $this->thingsToHalfTalk;
    }

    public function setThingsToHalfTalk(null|string $thingsToHalfTalk): static
    {
        $this->thingsToHalfTalk = $thingsToHalfTalk;

        return $this;
    }

    public function getBanedTopics(): null|string
    {
        return $this->banedTopics;
    }

    public function setBanedTopics(null|string $banedTopics): static
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

    /**
     * Summary of getCurrentScene
     * @return null|Scene
     */
    public function getCurrentScene(): null|Scene
    {
        return false === $this->scenes->last() ? null : $this->scenes->last();
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

    public function getAuthor(): null|User
    {
        return $this->author;
    }

    /**
     * Summary of setAuthor
     * @param \Symfony\Component\Security\Core\User\UserInterface|\App\Entity\User|null $author
     * @throws \InvalidArgumentException
     * @return Self
     */
    public function setAuthor(UserInterface|User|null $author): self
    {
        if (!($author instanceof User || null === $author)) {
            throw new \InvalidArgumentException();
        }
        $this->author = $author;

        return $this;
    }

    public function getState(): null|GameState
    {
        return $this->state;
    }

    public function setState(GameState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPassword(): null|string
    {
        return $this->password;
    }

    public function setPassword(string $password, ?PasswordHasherFactoryInterface $hasherFactory = null): static
    {
        if ($hasherFactory) {
            $hasher = $hasherFactory->getPasswordHasher('App\Entity\Game');
            $this->password = $hasher->hash($password);
        } else {
            $this->password = $password;
        }

        return $this;
    }

    public function isPasswordValid(string $plainPassword, ?PasswordHasherFactoryInterface $hasherFactory = null): bool
    {
        if (!$hasherFactory) {
            return $plainPassword === ($this->password ?? '');
        }

        if (!$this->password) {
            return false;
        }

        $hasher = $hasherFactory->getPasswordHasher('App\Entity\Game');
        return $hasher->verify($this->password, $plainPassword);
    }

    /**
     * @return GameRoles[]
     */
    public function getRoles(null|string $type = null): array
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

    /**
     * @throws \TypeError
     * @throws \ValueError
     * @return Player[]
     */
    public function getPlayersWithPreferedRole(string|GameRoles $role): array
    {
        if (!$role instanceof GameRoles) {
            $role = GameRoles::from($role);
        }

        return $this->players->filter(function (Player $player) use ($role) {
            return in_array($role, $player->getPreferedRoles() ?? []);
        })->toArray();
    }

    public function getRadianceTokenLimit(): int
    {
        return $this->maxPlayers * self::RADIANCE_TOKENS_PER_PLAYER;
    }

    public function getCurrentTotalRadianceTokens(): int
    {
        return $this->getPlayers()->reduce(function (int $carry, Player $player) {
            return $carry + (int) $player->getRadianceToken();
        }, 0);
    }

    public function radianceLimitReached(): bool
    {
        return $this->getCurrentTotalRadianceTokens() >= $this->getRadianceTokenLimit();
    }

    public function userIsPlayer(null|User $user): bool
    {
        if (null === $user) {
            return false;
        }
        $player = $this->players->findFirst(function (int $_index, Player $player) use ($user): bool {
            return $player->getLinkedUser() === $user;
        });

        return $player ? true : false;
    }
}
