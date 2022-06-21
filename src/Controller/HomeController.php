<?php

namespace App\Controller;

use App\Entity\Relationship;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\PostRepository;
use App\Repository\ReactionRepository;
use App\Repository\RelationshipRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route ("/processLogin", name="process_login")
     */
    public function processLogin(UserRepository $userRepository, ManagerRegistry $managerRegistry)
    {
        $user = $userRepository->find($this->getUser()->getId());
        $user->setLoginStatus('online');

        $database = $managerRegistry->getManager();
        $database->persist($user);
        $database->flush();

        return $this->redirectToRoute('app_home');
    }
    
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ReactionRepository $reactionRepository, CommentRepository $commentRepository, UserRepository $userRepository, PostRepository $postRepository, RelationshipRepository $relationshipRepository, NotificationRepository $notificationRepository)
    {
        //get avatar header
        $inforNavBar = $userRepository->getUserInforNavBar($this->getUser()->getId());

        //get total notification of like and comment
        $liekNotification = $notificationRepository->getLikeFromOtherUser($this->getUser()->getId());
        $commentNotification = $notificationRepository->getCommentFromOtherUser($this->getUser()->getId());
        $totalLikeAndCommentNotification = $liekNotification[0]['total_like'] + $commentNotification[0]['total_comment'];

        //get notification of invite friend
        $inviteFriend = $notificationRepository->getInvitefriend($this->getUser()->getId());

        //get detail notification
        $likeAndCommentDetail = $notificationRepository->getCommentAndLikeDetailFromOtherUser($this->getUser()->getId());
        $inviteFriendDetail = $notificationRepository->getInviteFriendDetail($this->getUser()->getId());
        //get friend list
        $friendList = $relationshipRepository->getFriendList($this->getUser()->getId());

        //get post and data user
        $post = $postRepository->getPost($this->getUser()->getId());
        $postLiked = $reactionRepository->checklike($this->getUser()->getId());
        $userInfor = $userRepository->getProfile($this->getUser()->getId());
        //get comment
        $comments = $commentRepository->getFullComment();

        return $this->render('home/homeIndex.html.twig',[
            'inforNavBar' => $inforNavBar,
            'countlikeAndComment' => $totalLikeAndCommentNotification,
            'countInviteFriend' => $inviteFriend,
            'likeAndCommentDetail' => $likeAndCommentDetail,
            'inviteFriendDetail' => $inviteFriendDetail,
            'inforUser' => $userInfor,
            'posts'=> $post,
            'postLiked' => $postLiked,
            'comments' => $comments,
            'friendList' => $friendList
        ]);
    }


    //=====================================Rest API call by AJAX===================================================================

    /**
     * @Route ("/searchUser", name="search_user", methods={"GET"})
     */
    public function searchUser(Request $request, UserRepository $userRepository)
    {
        $request = $this->tranform($request);
        $userFullname = $request->get('fullname');

        $userList = $userRepository->searchUserWithFullName($userFullname);

        if($userList)
        {
             return new JsonResponse(['status_code' => 200, 'userList' => $userList]);
        }
        else
        {
            return new JsonResponse(['status_code' => 400, 'Message' => 'Not found user with name: '.$userFullname]);
        }
    }

        public function tranform($request){
        $data = json_decode($request->getContent(), true);
        if($data === null){
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }


}
