<?php

namespace App\Repository;

use App\Entity\CollectionCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CollectionCard>
 *
 * @method CollectionCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionCard[]    findAll()
 * @method CollectionCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectionCard::class);
    }

    public function findByExampleField($value): array
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.cards', 'c')
            ->addSelect('u, SUM(c.price) as totalPrice')
            ->groupBy('u.id')
            ->orderBy('totalPrice', 'DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }
}
