<?php

namespace App\Repository;

use App\Entity\Lodging;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Lodging>
 *
 * @method Lodging|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lodging|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lodging[]    findAll()
 * @method Lodging[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LodgingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lodging::class);
    }

    public function add(Lodging $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lodging $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
    * @return Lodging[] Returns an array of Lodging objects
    */
    public function findByCategory($category): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.category = :val')
            ->setParameter('val', $category)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Lodging[] Returns an array of Lodging objects
     */
    public function findByFilter(array $filter): array
    {
        $query = $this->createQueryBuilder('l');

        if (isset($filter['lodging'])) {
            $query->andWhere('l.category = :category')
                ->setParameter('category', $filter['lodging']);
        }

        if (isset($filter['location'])) {
            $query->andWhere('l.location = :location')
                ->setParameter('location', $filter['location']);
        }

        if (isset($filter['adults'])) {
            $nbPeople = $filter['adults'] + $filter['children'];
            $query->andWhere('l.host_capacity >= :nbPeople')
                ->setParameter('nbPeople', $nbPeople);
        }

        return $query->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?Lodging
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
