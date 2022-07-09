<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeZone;
/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    #Get all friend's post and don't over 3 days 
    public function getPost($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ="SELECT u.id as ownerPost, u.avatar,u.fullname,p.caption,p.image,p.total_like,p.total_comment,p.upload_time,p.id
        FROM post as p, user as u, relationship as r WHERE
        (r.user_id=:user_id AND p.user_id=r.friend_id AND u.id=r.friend_id 
        AND r.status=1 AND p.deleted='false' and DATE(P.upload_time)+3>DATE(NOW())) or 
        (r.friend_id=:user_id AND r.friend_id=p.user_id AND u.id=p.user_id AND p.deleted='false' and DATE(P.upload_time)+3>DATE(NOW()))
        GROUP BY p.id ORDER BY RAND()";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);
        return $resultSet->fetchAllAssociative();
    }

   return $resultSet->fetchAllAssociative();
    }
    // Use post_id to get user_id 
    public function getUserIdFromAPost($post_id)
    {
        $conn=$this->getEntityManager()->getConnection();
        $query ="SELECT u.id FROM user as u, post as p WHERE p.id=:post_id AND u.id=p.user_id AND p.deleted='false'";
        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['post_id'=>$post_id]);
        return $resultSet->fetchAllAssociative();
    }


//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
