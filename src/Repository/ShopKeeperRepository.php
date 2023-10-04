<?php

namespace App\Repository;

use App\Entity\ShopKeeper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShopKeeper>
 *
 * @method ShopKeeper|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopKeeper|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopKeeper[]    findAll()
 * @method ShopKeeper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopKeeperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopKeeper::class);
    }

//    /**
//     * @return ShopKeeper[] Returns an array of ShopKeeper objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShopKeeper
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getFiltered(array $filters)
    {
        $qb = $this->createQueryBuilder('sk');

        if (!empty($filters['name'])) {
            $qb->andWhere('sk.name LIKE :name')
                ->setParameter(':name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['ville'])) {
            $qb->andWhere('sk.city LIKE :ville')
                ->setParameter(':ville', '%' . $filters['ville'] . '%');
        }

        if (!empty($filters['region'])) {
            $qb->andWhere('sk.postalCode LIKE :region')
                ->setParameter(':region', $filters['region'] . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
