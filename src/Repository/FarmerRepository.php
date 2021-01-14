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

    public function farmersPerCity()
    {
        return $this->createQueryBuilder('farmer')
            ->select('COUNT(farmer.id), farmer.city, farmer.id')
            ->groupBy('farmer.city')
            ->getQuery()
            ->execute();
    }

    public function findFarmersInCity(string $city)
    {
        $queryBuilder = $this->createQueryBuilder('farmer')
            ->join('farmer.city', 'city')
            ->where('city.city LIKE :city')
            ->setParameter('city', '%' . $city . '%')
            ->setMaxResults(20)
            ->getQuery();

        return $queryBuilder->getResult();
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
