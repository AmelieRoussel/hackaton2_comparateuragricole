<?php

namespace App\Repository;

use App\Entity\Farmer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Farmer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Farmer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Farmer[]    findAll()
 * @method Farmer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FarmerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Farmer::class);
    }

    public function findFarmersInCity(string $slug)
    {
        $queryBuilder = $this->createQueryBuilder('farmer')
            ->join('farmer.city', 'city')
            ->where('city.slug LIKE :slug')
            ->setParameter('slug', '%' . $slug . '%')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findFarmersInDepartment($department)
    {
        $queryBuilder = $this->createQueryBuilder('farmer')
            ->join('farmer.city', 'city')
            ->where('city.department = :department')
            ->setParameter('department', $department)
            ->setMaxResults(50)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findFarmersWithData()
    {
        return $this->createQueryBuilder('farmer')
            ->select('farmer')
            ->join('App\Entity\City', 'city', 'WITH', 'city.id=farmer.city')
            ->join('App\Entity\Transaction', 'transaction', 'WITH', 'farmer.id=transaction.farmer')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Farmer[] Returns an array of Farmer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Farmer
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
