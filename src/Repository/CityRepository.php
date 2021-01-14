<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findCitiesWithFarmers()
    {
        return $this->createQueryBuilder('city')
            ->join('city.farmers', 'farmers')
            ->orderBy('city.city', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function findDepartmentsWithFarmers()
    {
        return $this->createQueryBuilder('city')
            ->join('city.farmers', 'farmers')
            ->orderBy('city.department', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function findFarmersInCity(string $slug)
    {
        $queryBuilder = $this->createQueryBuilder('city')
            ->join('city.farmers', 'farmers')
            ->where('city.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findFarmersInDepartment($department)
    {
        $queryBuilder = $this->createQueryBuilder('city')
            ->join('city.farmers', 'farmers')
            ->where('city.department = :department')
            ->setParameter('department', $department)
            ->getQuery();

        return $queryBuilder->getResult();
    }

    // /**
    //  * @return City[] Returns an array of City objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
