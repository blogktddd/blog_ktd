<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    // Get profile of current user
    public function getProfile($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ="select u.id, u.fullname,
        u.avatar,u.phone,u.gender,
        u.address,u.email,
        (SELECT COUNT(p.id)FROM post as p WHERE p.user_id=:user_id AND p.deleted='false') as total_post,
        (SELECT COUNT(r.id) FROM relationship as r where r.user_id=:user_id and r.status=1) as total_friend
        from user as u 
        where u.id=:user_id";

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);

        return $resultSet->fetchAllAssociative();
    }

    public function getUserInforNavBar($user_id)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ='SELECT u.id, u.avatar from user as u WHERE u.id=:user_id';

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['user_id'=>$user_id]);

        return $resultSet->fetchAllAssociative();
    }
    public function searchUserWithFullName($fullname)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ="SELECT u.id,u.avatar,u.fullname,
        (SELECT COUNT(p.id) from post as p, user as u where 
        u.fullname like :fullname and u.id=p.user_id AND p.deleted='false') as count_post, 
        (SELECT COUNT(r.user_id) FROM relationship as r, user as u 
        WHERE u.fullname like :fullname AND u.id=r.user_id AND r.status=1)as count_friend
        from user as u WHERE u.fullname like :fullname";

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['fullname'=>$fullname]);

        return $resultSet->fetchAllAssociative();
    }

    public function checkUserName($username)
    {
        $conn=$this->getEntityManager()->getConnection();

        $query ="SELECT count(u.id) as count_user from user as u WHERE u.username = :username";

        $stmt=$conn->prepare($query);
        $resultSet=$stmt->executeQuery(['username'=>$username]);

        return $resultSet->fetchAllAssociative();
    }


    
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
