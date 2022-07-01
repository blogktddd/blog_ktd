<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\Type\updateProfileFormType;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\PostRepository;
use App\Repository\ReactionRepository;
use App\Repository\RelationshipRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use phpDocumentor\Reflection\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\Type\changesPasswordFormType;


class ProfileController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Route("/profile/{userId}", name="app_profile", methods={"GET"})
     */
    public function index($userId, CommentRepository $commentRepository, NotificationRepository $notificationRepository, RelationshipRepository $relationshipRepository, UserRepository $userRepository, PostRepository $postRepository, ReactionRepository $reactionRepository)
    {
        $error = false;
        $caption = '';
        $relationshipStatus = '';

        //get infor navbar
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

        //get data user
        $userInfor = $userRepository->getProfile($userId);
        $posts = $postRepository->getPostProfile($userId);
        $postLiked = $reactionRepository->checklike($this->getUser()->getId());

        //get comment
        $comments = $commentRepository->getFullComment();

        if($userId != $this->getUser()->getId())
        {
            $relationshipStatus = $relationshipRepository->checkRelatonshipStatus($this->getUser()->getId(), $userId);
            return $this->render( 'profile/profileIndex.html.twig',[
                'error' => $error,
                'caption' => $caption,
                'inforNavBar' => $inforNavBar,
                'countlikeAndComment' => $totalLikeAndCommentNotification,
                'countInviteFriend' => $inviteFriend,
                'likeAndCommentDetail' => $likeAndCommentDetail,
                'inviteFriendDetail' => $inviteFriendDetail,
                'inforUser' => $userInfor,
                'posts' => $posts,
                'postLiked' => $postLiked,
                'comments' => $comments,
                'friendStatus' => $relationshipStatus,
            ]);
//            return new JsonResponse($relationshipStatus);
        }
        else
        {
            return $this->render('profile/profileIndex.html.twig', [
                'error' => $error,
                'caption' => $caption,
                'inforNavBar' => $inforNavBar,
                'countlikeAndComment' => $totalLikeAndCommentNotification,
                'countInviteFriend' => $inviteFriend,
                'likeAndCommentDetail' => $likeAndCommentDetail,
                'inviteFriendDetail' => $inviteFriendDetail,
                'inforUser' => $userInfor,
                'posts' => $posts,
                'postLiked' => $postLiked,
                'comments' => $comments,
                'friendStatus' => $relationshipStatus,
            ]);
        }

    }

    /**
     * @Route ("/updateInformation", name="app_update_profile", methods={"POST", "GET"})
     */
    public function updateInforProfileAction(Request $request, NotificationRepository $notificationRepository, UserRepository $userRepository, ValidatorInterface $validator, ManagerRegistry $managerRegistry)
    {
        //get infor navbar
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

        $inforUser = $userRepository->getUserInforNavBar($this->getUser()->getId());
        $errorUpdateInfor = null;

        $user = new User();
        $formUpdateInfor = $this->createForm(updateProfileFormType::class, $user);
        $formUpdateInfor->handleRequest($request);

        if($formUpdateInfor->isSubmitted())
        {
            if($formUpdateInfor->isValid())
            {
                $user = $userRepository->find($this->getUser()->getId());
                $dataInforUpdate = $formUpdateInfor->getData();

                $user->setUsername($this->getUser()->getUsername());
                $user->setPassword($this->getUser()->getPassword());
                $user->setFullname($dataInforUpdate->getFullname());
                $user->setEmail($dataInforUpdate->getEmail());
                $user->setPhone($dataInforUpdate->getPhone());
                $user->setAddress($dataInforUpdate->getAddress());

                $database = $managerRegistry->getManager();
                $database->persist($user);
                $database->flush();

                $formUpdateInfor = $this->createForm(updateProfileFormType::class, $user);
            }
            else
            {
                $errorUpdateInfor = $validator->validate($user);
            }

        }
        else
        {

            $inforUpdate = $userRepository->getProfile($this->getUser()->getId());

            $user->setFullname($inforUpdate[0]['fullname']);
            $user->setEmail($inforUpdate[0]['email']);
            $user->setGender($inforUpdate[0]['gender']);
            $user->setPhone($inforUpdate[0]['phone']);
            $user->setAddress($inforUpdate[0]['address']);
            $formUpdateInfor = $this->createForm(updateProfileFormType::class, $user);
        }


        return $this->render('profile/profileUpdateInfor.html.twig',[
            'inforNavBar' => $inforNavBar,
            'countlikeAndComment' => $totalLikeAndCommentNotification,
            'countInviteFriend' => $inviteFriend,
            'likeAndCommentDetail' => $likeAndCommentDetail,
            'inviteFriendDetail' => $inviteFriendDetail,
            'errorUpdateInfor' => $errorUpdateInfor,
            'inforUser' => $inforUser,
            'updateInforForm' => $formUpdateInfor->createView()
        ]);

//        return new JsonResponse(['inforUpdate' => $inforUpdate]);
    }

    //=====================================Routing for API request by AJAX=================================

    /**
     * @Route ("/profile/changeAvatar", name="app_change_avatar", methods="POST")
     */
    public function changeAvatarProfileAction(ManagerRegistry $managerRegistry, UserRepository $userRepository)
    {

        $caption = $_POST['captionChangeAvatar'];
        $imgFile = $_FILES['imgAvatar'];

        $error = $this->checkPost($imgFile, $caption);

        if($error != '')
        {
            return new JsonResponse(['status_code' => 400, 'Message' => $error]);
        }
        $safeFileImg = uniqid().$imgFile['name'];
        copy($imgFile['tmp_name'], "image/post/".$safeFileImg);

        //get user data
        $userId = $this->getUser()->getId();
        $user = $userRepository->find($userId);

        //set data for user
        $user->setAvatar($safeFileImg);

        //save infor user
        $database = $managerRegistry->getManager();
        $database->persist($user);
        $database->flush($user);

        $post = new Post();
        $post->setUser($user);
        $post->setCaption($caption);
        $post->setImage($safeFileImg);
        $post->setUploadTime(new \DateTime());
        $post->setDeleted('false');

        $database->persist($post);
        $database->flush();

        return new JsonResponse([
            'status_code' => 200,
            'Message' => 'success',
            'userId' => $this->getUser()->getId()
        ]);
    }


    public function checkPost($imgFile, $caption)
    {
        $error = '';
        if($imgFile['name'] != '')
        {
            if(!($imgFile["type"] =="image/jpg" || $imgFile['type'] == "image/jpeg" || $imgFile['type'] == "image/png"))
            {
                  $error = 'Only accept image';
            }
        }
        else if($imgFile['name'] != '' && $caption == '')
        {
            $error = 'content is empty';
        }

        return $error;
    }

    public function tranform($request){
        $data = json_decode($request->getContent(), true);
        if($data === null){
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }

    /**
     * @Route("/changePassword", name="change_password")
     */
    public function changePassword(Request $request,UserPasswordHasherInterface $hasher,UserRepository $userRepository,ManagerRegistry $managerRegistry): Response
    {
        $user=new user();        
        $changePassword=$this->createForm(changesPasswordFormType::class,$user);
        $changePassword->handleRequest($request);
        $error = false;
        $message = "";

        if($changePassword->isSubmitted())
        {
            if($changePassword->isValid())
            {
                $password = $this->getUser();
                $oldPassword = $request->request->get('password');
                $newPassword = $request->request->get('newPassword');
                $confirmPassword = $request->request->get('confirmPassword');

                if ($hasher->isPasswordValid($password, $oldPassword))
                {
                    if ($newPassword == $confirmPassword)
                    {
                        $User = $userRepository->find($this->getUser()->getId());
                        $User->setPassword($this->passwordHasher->HashPassword($user, $newPassword));
                        $database = $managerRegistry->getManager();
                        $database->persist($User);
                        $database->flush();
                        $message = 'Change Password is success';
                    }
                }
                else
                {
                    $error = true;
                    $message = 'Incorrect Current Password ';
                }

            }
            else
            {
                $error = true;
                $message = 'invalid Password';
            }
        }

        return $this->render('profile/changePassword.html.twig', [
            'error' => $error,
            'message' => $message,
            'changes_password' => $changePassword->createView()
        ]);
    }
}
