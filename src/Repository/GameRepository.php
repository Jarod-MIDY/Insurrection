<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use App\Enum\GameState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $game, bool $flush = false): void
    {
        $this->getEntityManager()->persist($game);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $game, bool $flush): void
    {
        $this->getEntityManager()->remove($game);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param null|\App\Entity\User $author
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return Game|null
     */
    public function findUnfinishedOrNull(null|User $author = null): null|Game
    {
        /**
         * @var Game|null $result
         */
        $result = $this->createQueryBuilder('game')
            ->andWhere('game.author = :author')
            ->andWhere('game.state != :state')
            ->setParameter('state', GameState::CLOSED)
            ->setParameter('author', $author)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}
