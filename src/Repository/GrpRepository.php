<?php

namespace App\Repository;

use App\Entity\Grp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Grp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grp[]    findAll()
 * @method Grp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrpRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Grp::class);
    }

    // /**
    //  * @return Grp[] Returns an array of Grp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Grp
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function transform(Grp $group)
    {
        return [
            'id'    => (int) $group->getId(),
            'name' => (string) $group->getName(),
            'users' =>  $group->getUsers()
        ];
    }

    /**
     * * @method grp findAll()
     */
    public function transformAll()
    {
        $groups = $this->findAll();
        $groupsArray = [];
        foreach ($groups as $group){
            $groupsArray[] = $this->transform($group);
        }
        return $groupsArray;
    }

}
