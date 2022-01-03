<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByOne()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->from('products', 'c')
            // ->where()
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllGreaterThanPrice(): array
    {
        $connect = $this->getEntityManager()->getConnection();

        $query = '
            SELECT * FROM `products`
            INNER JOIN `products_categories` ON `products`.`id` = `products_categories`.`products_id`
            INNER JOIN `categories` ON `categories`.`id` = `products_categories`.`categories_id`
            WHERE `products`.id = 7
            ';
        $statement = $connect->prepare($query);
        $statement->executeQuery();
        $results = $statement->fetchAllAssociativeArray();
        // returns an array of arrays (i.e. a raw data set)
        return $results;
    }
}
