<?php

namespace App\Entity;

use App\Repository\SceneRepository;
use App\Traits\TraitTimestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceneRepository::class)]
class Scene
{
    use TraitTimestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public null|int $id = null;

    #[ORM\ManyToOne(inversedBy: 'scenes')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Game $game = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'scenes')]
    private Collection $characters;

    #[ORM\Column(nullable: true)]
    private null|int $estimatedDuration = 0;

    #[ORM\Column(nullable: true)]
    private null|\DateTimeImmutable $finishedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private null|string $goal = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'dyingScene')]
    private Collection $deadCharacters;

    /**
     * @var Collection<int, SceneLeaderVote>
     */
    #[ORM\OneToMany(targetEntity: SceneLeaderVote::class, mappedBy: 'scene', orphanRemoval: true)]
    private Collection $sceneLeaderVotes;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private null|Player $leader = null;

    /**
     * @var Collection<int, SceneStory>
     */
    #[ORM\OneToMany(targetEntity: SceneStory::class, mappedBy: 'scene')]
    private Collection $stories;

    #[ORM\Column(nullable: true)]
    private null|\DateTimeImmutable $startedAt = null;

    /**
     * @var Collection<int, TokenAction>
     */
    #[ORM\OneToMany(targetEntity: TokenAction::class, mappedBy: 'scene')]
    private Collection $tokenActions;

    /**
     * @var array<int, array{
     *     'readyStatus': bool,
     *     'date': ?\DateTimeImmutable,
     * }>
     */
    #[ORM\Column(nullable: false, type: Types::JSON)]
    private array $readyLogs = [];

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->deadCharacters = new ArrayCollection();
        $this->sceneLeaderVotes = new ArrayCollection();
        $this->stories = new ArrayCollection();
        $this->tokenActions = new ArrayCollection();
    }

    public function getId(): null|int
    {
        return $this->id;
    }

    public function getGame(): null|Game
    {
        return $this->game;
    }

    public function setGame(null|Game $game): static
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

    public function getEstimatedDuration(): int
    {
        return (int) $this->estimatedDuration;
    }

    public function setEstimatedDuration(null|int $estimatedDuration): static
    {
        $this->estimatedDuration = $estimatedDuration;

        return $this;
    }

    public function getEstimatedDateEnd(): null|\DateTimeInterface
    {
        return null !== $this->startedAt
            ? $this->startedAt->add(new \DateInterval('PT' . $this->getEstimatedDuration() . 'M'))
            : null;
    }

    public function getGoal(): null|string
    {
        return $this->goal;
    }

    public function setGoal(null|string $goal): static
    {
        $this->goal = $goal;

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

    /**
     * @return Collection<int, SceneLeaderVote>
     */
    public function getSceneLeaderVotes(): Collection
    {
        return $this->sceneLeaderVotes;
    }

    public function addSceneLeaderVote(SceneLeaderVote $sceneLeaderVote): static
    {
        if (!$this->sceneLeaderVotes->contains($sceneLeaderVote)) {
            $this->sceneLeaderVotes->add($sceneLeaderVote);
            $sceneLeaderVote->setScene($this);
        }

        return $this;
    }

    public function removeSceneLeaderVote(SceneLeaderVote $sceneLeaderVote): static
    {
        if ($this->sceneLeaderVotes->removeElement($sceneLeaderVote)) {
            // set the owning side to null (unless already changed)
            if ($sceneLeaderVote->getScene() === $this) {
                $sceneLeaderVote->setScene(null);
            }
        }

        return $this;
    }

    public function getLeader(): null|Player
    {
        return $this->leader;
    }

    public function setLeader(null|Player $leader): static
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * @return Collection<int, SceneStory>
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(SceneStory $story): static
    {
        if (!$this->stories->contains($story)) {
            $this->stories->add($story);
            $story->setScene($this);
        }

        return $this;
    }

    public function removeStory(SceneStory $story): static
    {
        if ($this->stories->removeElement($story)) {
            // set the owning side to null (unless already changed)
            if ($story->getScene() === $this) {
                $story->setScene(null);
            }
        }

        return $this;
    }

    public function getStartedAt(): null|\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(null|\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function isStarted(): bool
    {
        return null !== $this->startedAt && $this->startedAt <= new \DateTimeImmutable();
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
            $tokenAction->setScene($this);
        }

        return $this;
    }

    public function removeTokenAction(TokenAction $tokenAction): static
    {
        if ($this->tokenActions->removeElement($tokenAction)) {
            // set the owning side to null (unless already changed)
            if ($tokenAction->getScene() === $this) {
                $tokenAction->setScene(null);
            }
        }

        return $this;
    }

    /**
     * Summary of setReadyPlayer
     * @param \App\Entity\Player $player
     * @throws \InvalidArgumentException
     * @return static
     */
    public function setReadyPlayer(Player $player): static
    {
        $id = $player->getId();
        if (null === $id) {
            throw new \InvalidArgumentException('Invalid player id in ready logs of scene ' . (string) $this->getId());
        }
        $ready = key_exists($id, $this->readyLogs) ? !$this->readyLogs[$id]['readyStatus'] : true;
        $this->readyLogs[$id] = ['readyStatus' => $ready, 'date' => new \DateTimeImmutable()];
        $this->startScene();

        return $this;
    }

    /**
     * Summary of isPlayerReady
     * @param \App\Entity\Player $player
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function isPlayerReady(Player $player): bool
    {
        $id = $player->getId();
        if (null === $id) {
            throw new \InvalidArgumentException('Invalid player id in ready logs of scene ' . (string) $this->getId());
        }

        return key_exists($id, $this->readyLogs) && $this->readyLogs[$id]['readyStatus'];
    }

    /**
     * Summary of startScene
     * @throws \InvalidArgumentException
     * @return void
     */
    private function startScene(): void
    {
        $game = $this->getGame();
        if (null === $game) {
            throw new \InvalidArgumentException('Invalid game, cannot start scene ');
        }
        $nbReadyPlayer = 0;
        foreach ($this->readyLogs as $key => $log) {
            $foundPlayer = $game->getPlayers()->findFirst(function (int $_index, Player $player) use ($key): bool {
                return $player->getId() === $key;
            });
            if (null === $foundPlayer) {
                throw new \InvalidArgumentException('Invalid player id '
                . $key
                . ' in ready logs of scene '
                . (string) $this->getId());
            }
            if (key_exists('readyStatus', $log) && $log['readyStatus']) {
                ++$nbReadyPlayer;
            }
        }
        if ($nbReadyPlayer === $game->getMaxPlayers()) {
            $this->setStartedAt(new \DateTimeImmutable('+10 seconds'));
        }
    }

    /**
     * Get the value of finishedAt.
     */
    public function getFinishedAt(): null|\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * Set the value of finishedAt.
     */
    public function setFinishedAt(null|\DateTimeImmutable $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
