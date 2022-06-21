<?php

namespace App\Repository;

use App\Entity\Reaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reaction>
 *
 * @method Reaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reaction[]    findAll()
 * @method Reaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reaction::class);
    }

    public function add(Reaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // this function will count the row if that return 1 verify that the user was liked this post and 0 is not
    public function checklike($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query='SELECT p.id as liked
        FROM post as p, reaction as r WHERE
         r.user_id=:user_id AND r.post_id=p.id and p.deleted="false"
         ORDER BY p.upload_time DESC';
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);
        return $resultSet->fetchAllAssociative();
    }
    public function getReactionId($user_id,$post_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query='SELECT r.id FROM reaction as r WHERE r.user_id=:user_id AND r.post_id=:post_id';
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id,'post_id'=>$post_id]);
        return $resultSet->fetchAllAssociative();
    }



   
//    /**
//     * @return Reaction[] Returns an array of Reaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reaction
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
