<?php

namespace App\Repository;

use App\Entity\InfluenceToken;
use App\Entity\Player;
use App\Enum\GameRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfluenceToken>
 *
 * @method InfluenceToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfluenceToken|null findOneBy(mixed[] $criteria, mixed[] $orderBy = null)
 * @method InfluenceToken[]    findAll()
 * @method InfluenceToken[]    findBy(mixed[] $criteria, mixed[] $orderBy = null, $limit = null, $offset = null)
 */
class InfluenceTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfluenceToken::class);
    }

    public function findUsableToken(Player $receiver, GameRoles $senderRole): ?InfluenceToken
    {
        return $this->createQueryBuilder('influenceToken')
            ->join('influenceToken.sender', 'sender')
            ->where('influenceToken.receiver = :receiver')
            ->andWhere('sender.role = :role ')
            ->andWhere('influenceToken.isUsed = false')
            ->setParameter('role', $senderRole)
            ->setParameter('receiver', $receiver)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
