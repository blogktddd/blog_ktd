<?php

namespace App\Repository;

use App\Entity\Relationship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relationship>
 *
 * @method Relationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relationship[]    findAll()
 * @method Relationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relationship::class);
    }

    public function add(Relationship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Relationship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    #show Friend List of current user
    public function getFriendList($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ='select u.id,u.avatar,u.fullname,u.login_status from relationship as r, user as u
        where r.status=1 AND r.friend_id=u.id AND r.user_id=:user_id';
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);
        return $resultSet->fetchAllAssociative(); 
    }
// this function will return 0 1 2. 0 is there are not friend 1 is has send invite and 2 is there are friend
    public function checkRelatonshipStatus($user_id,$friend_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ="SELECT count(r.id) as friendStatus, r.friend_id from relationship as r WHERE
        r.user_id=:user_id AND r.friend_id=:friend_id or r.user_id=:friend_id AND r.friend_id=:user_id";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id,'friend_id'=>$friend_id]);
        return $resultSet->fetchAllAssociative(); 
    }

    public function unfriend($user_id,$friend_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ='DELETE from relationship WHERE
        relationship.user_id=:user_id AND relationship.friend_id=:friend_id 
        AND relationship.status=1 or relationship.user_id=:friend_id AND 
        relationship.friend_id=:user_id AND relationship.status=1';
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id,'friend_id'=>$friend_id]);
        return $resultSet;
    }
    // because we login by the account which be requested add friend so friend_id is the current user_id
    public function updateStatus($userId,$senderId)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ='UPDATE relationship set relationship.status=1 
        WHERE (relationship.user_id=:sender_id AND relationship.friend_id=:user_id) OR (relationship.friend_id=:sender_id AND relationship.user_id=:user_id)';
        $stmt=$conn->prepare($query);
        $stmt->executeQuery(['user_id'=>$userId,'sender_id'=>$senderId]);
        return $stmt;
    }



    
//    /**
//     * @return Relationship[] Returns an array of Relationship objects
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

//    public function findOneBySomeField($value): ?Relationship
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
