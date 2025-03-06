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

    public function save(Game $game, bool $flush = false)
    {
        $this->getEntityManager()->persist($game);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $game, bool $flush)
    {
        $this->getEntityManager()->remove($game);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUnfinishedOrNull(?User $author = null): ?Game
    {
        return $this->createQueryBuilder('game')
            ->andWhere('game.author = :author')
            ->andWhere('game.state != :state')
            ->setParameter('state', GameState::CLOSED)
            ->setParameter('author', $author)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
