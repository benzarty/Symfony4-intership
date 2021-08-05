<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    function SearchAdmin($nsc) //requette en plusieur etape
    {
        return $this->createQueryBuilder('s')
            ->where('s.nom LIKE :nom')
            ->setParameter('nom','%'.$nsc.'%')
            ->andWhere('s.role LIKE :admin')
            ->setParameter('admin','admin')   //label valeur
            ->getQuery()
            ->getResult();
    }
    function SearchUsers($nsc) //requette en plusieur etape
    {
        return $this->createQueryBuilder('s')
            ->where('s.nom LIKE :nom')
            ->setParameter('nom','%'.$nsc.'%')
            ->andWhere('s.role LIKE :admin')
            ->setParameter('admin','users')   //label valeur
            ->getQuery()
            ->getResult();
    }
}
