<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function add(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    #this function will count all the like of other user who was react the current user's post
    public function getLikeFromOtherUser($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ='SELECT COUNT(n.id) as total_like FROM notification as n where n.type="like" AND n.seen="no" AND n.receiver_id=:user_id';

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);

        return $resultSet->fetchAllAssociative();
    }
     #this function will count all the comment of other user who was react the current user's post
    public function getCommentFromOtherUser($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query='SELECT COUNT(n.id) as total_comment FROM notification as n where n.type="comment" AND n.seen="no" AND n.receiver_id=:user_id';
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);
        return $resultSet->fetchAllAssociative();
    }

    // get who has like or comment
    public function getCommentAndLikeDetailFromOtherUser($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ='SELECT n.id, u.fullname,n.type FROM notification as n ,user as u WHERE n.receiver_id=:user_id AND u.id=n.sender_id ORDER BY n.id DESC';

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);

        return $resultSet->fetchAllAssociative();
    }

    #count all invite friend request for current user
    public function getInvitefriend($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ='
        SELECT COUNT(r.friend_id) as invite_friend_request
        FROM relationship as r ,user as u
        WHERE r.friend_id=:user_id and r.status=0 and u.id=r.user_id';

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);

        return $resultSet->fetchAllAssociative();
    }
// get name who want to be friend with current user
    public function getInviteFriendDetail($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ="SELECT n.id,u.fullname,n.sender_id from notification as n, user as u where u.id=n.sender_id AND n.receiver_id=:user_id AND n.type='invite' order by n.id DESC";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);

        return $resultSet->fetchAllAssociative();
    }
    // update all seen become "yes" where the user click to see the notification of like and comment
    public function updateSeenStatusNotification($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ="UPDATE notification as n SET n.seen='yes' WHERE n.receiver_id=:user_id AND (n.type='comment' OR n.type='like')";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);
        return $resultSet;
    }
    // update all seen become "yes" where the user click to see the notification of friend
    public function updateSeenStatusNotificationOfFriend($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ="UPDATE notification as n SET n.seen='yes' WHERE n.receiver_id=:user_id AND n.type='invite'";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);
        return $resultSet;
    }

    public function removeNotificationLike($sender_id,$receiver_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query="DELETE FROM notification WHERE sender_id=:sender_id AND receiver_id=:receiver_id AND type='like'";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['sender_id'=>$sender_id,'receiver_id'=>$receiver_id]);
        return $resultSet;
    }
    public function removeNotificationComment($sender_id,$receiver_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query="DELETE FROM notification WHERE sender_id=:sender_id AND receiver_id=:receiver_id AND type='comment'";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['sender_id'=>$sender_id,'receiver_id'=>$receiver_id]);
        return $resultSet;
    }

//    /**
//     * @return Notification[] Returns an array of Notification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
