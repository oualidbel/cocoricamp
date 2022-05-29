<?php

namespace App\Repository;

use App\Entity\Lodging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

        // check if chekin date is in a reservation in reservation table

        if (isset($filter['check_in'])) {
            $query->andWhere('l.check_in >= :check_in')
                ->setParameter('check_in', $filter['check_in']);
        }

        if (isset($filter['check_out'])) {
            $query->andWhere('l.check_out <= :check_out')
                ->setParameter('check_out', $filter['check_out']);
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
