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
    public ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'scenes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'scenes')]
    private Collection $characters;

    #[ORM\Column(nullable: true)]
    private ?int $estimatedDuration = 0;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $goal = null;

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
    private ?Player $leader = null;

    /**
     * @var Collection<int, SceneStory>
     */
    #[ORM\OneToMany(targetEntity: SceneStory::class, mappedBy: 'scene')]
    private Collection $stories;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startedAt = null;

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

    public function getEstimatedDuration(): ?int
    {
        return $this->estimatedDuration;
    }

    public function setEstimatedDuration(?int $estimatedDuration): static
    {
        $this->estimatedDuration = $estimatedDuration;

        return $this;
    }

    public function getEstimatedDateEnd(): ?\DateTimeInterface
    {
        return null !== $this->startedAt ?
            $this->startedAt->add(new \DateInterval('PT'.$this->getEstimatedDuration().'M'))
            : null;
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

    public function getLeader(): ?Player
    {
        return $this->leader;
    }

    public function setLeader(?Player $leader): static
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

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): static
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

    public function setReadyPlayer(Player $player): static
    {
        if (null === $player->getId()) {
            throw new \Exception('Invalid player id in ready logs of scene '.$this->getId());
        }
        $ready = key_exists($player->getId(), $this->readyLogs) ?
                    !$this->readyLogs[$player->getId()]['readyStatus']
                    : true;
        $this->readyLogs[$player->getId()] = ['readyStatus' => $ready, 'date' => new \DateTimeImmutable()];
        $this->startScene();

        return $this;
    }

    public function isPlayerReady(Player $player): bool
    {
        if (null === $player->getId()) {
            throw new \Exception('Invalid player id in ready logs of scene '.$this->getId());
        }

        return key_exists($player->getId(), $this->readyLogs) && $this->readyLogs[$player->getId()]['readyStatus'];
    }

    private function startScene(): void
    {
        if (null === $this->getGame()) {
            throw new \Exception('Invalid game, cannot start scene ');
        }
        $nbReadyPlayer = 0;
        foreach ($this->readyLogs as $key => $log) {
            $foundPlayer = $this->getGame()->getPlayers()->findFirst(function (int $index, Player $player) use ($key): bool {
                return $player->getId() === $key;
            });
            if (null === $foundPlayer) {
                throw new \Exception('Invalid player id '.$key.' in ready logs of scene '.$this->getId());
            }
            if (key_exists('readyStatus', $log) && $log['readyStatus']) {
                ++$nbReadyPlayer;
            }
        }
        if ($nbReadyPlayer === $this->getGame()->getMaxPlayers()) {
            $this->setStartedAt(new \DateTimeImmutable('+10 seconds'));
        }
    }

    /**
     * Get the value of finishedAt.
     */
    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * Set the value of finishedAt.
     */
    public function setFinishedAt(?\DateTimeImmutable $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
