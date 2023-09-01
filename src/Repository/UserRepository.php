<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findUsersWithHighestTotalPrice()
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.cards', 'c')
            ->addSelect('u, SUM(c.price) as totalPrice')
            ->groupBy('u.id')
            ->orderBy('totalPrice', 'DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    public function findUserCard($card, $user)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.card_favoris', 'c')
            ->andWhere('c.user LIKE :user')
            ->andWhere('c.card LIKE :card')
            ->setParameter('card', $card)
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

}
