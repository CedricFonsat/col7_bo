<?php

namespace App\Repository;

use App\Entity\CardFavoris;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CardFavoris>
 *
 * @method CardFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardFavoris[]    findAll()
 * @method CardFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardFavoris::class);
    }

    public function findUserFavoriteCategory(User $user)
    {
        $qb = $this->createQueryBuilder('cf')
            ->leftJoin('cf.user', 'u')
            ->where('u like 8');

        dd($qb->getQuery()->getResult());

        return $qb->getQuery()->getResult();
    }
}
