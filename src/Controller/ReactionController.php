<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\Reaction;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\PostRepository;
use App\Repository\ReactionRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use DateTimeZone;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class ReactionController extends AbstractController
{
    /**
     * @Route("/reaction/like", name="app_reaction_like", methods={"PUT"})
     */
    public function likeAction(Request $request, NotificationRepository $notificationRepository,UserRepository $userRepository,PostRepository $postRepository, ReactionRepository $reactionRepository, ManagerRegistry $managerRegistry): Response
    {
        $request = $this->tranform($request);

        $idPostWantLike = $request->get('idPost');
        $optionLike = $request->get('optionLike');

        $post = $postRepository->find($idPostWantLike);
        $reaction = new  Reaction();

        $database = $managerRegistry->getManager();
        if($post)
        {
            if($optionLike == 'like')
            {
                $this->saveLike($reaction, $database, $post, $postRepository, $idPostWantLike, $userRepository);
                return new JsonResponse(['status_code' => 200, 'Message' => 'Like success with post id: '.$idPostWantLike]);
            }
            else
            {
                $reactionid = $this->unlike($post, $database, $reactionRepository, $idPostWantLike, $notificationRepository, $postRepository);
                return new JsonResponse(['status_code' => 200, 'Message' => 'Un like success with post id: '.$idPostWantLike, 'remove' => $reactionid[0]['id']]);
            }
        }
        else
        {
            return new JsonResponse(['status_code' => 400, 'Message' => 'Post id: '.$idPostWantLike.' not found' ]);
        }
    }
    function saveLike($reaction, $database, $post, $postRepository, $idPostWantLike, $userRepository)
    {
        //save like
        $reaction->setUser($this->getUser());
        $reaction->setPost($post);

        $database->persist($reaction);
        $database->flush();

        //save total like
        $post->setTotalLike($post->getTotalLike() + 1);
        $database->persist($reaction);
        $database->flush();

        //save notificacation
        $receiverId = $postRepository->getUserIdFromAPost($idPostWantLike);
        $receiver = $userRepository->find($receiverId[0]['id']);

        $notification = new Notification();
        $notification->setSender($this->getUser());
        $notification->setReceiver($receiver);
        $notification->setType('like');
        $notification->setSeen('no');

        $database->persist($notification);
        $database->flush();
    }
    function unlike($post, $database, $reactionRepository, $idPostWantLike, $notificationRepository, $postRepository)
    {
        //save total like
        $post->setTotalLike($post->getTotalLike() - 1);

        $database->persist($post);
        $database->flush();

        //remove like reaction
        $reactionid = $reactionRepository->getReactionId($this->getUser()->getId(), $idPostWantLike);
        $reactionWantDelete = $reactionRepository->find($reactionid[0]['id']);

        $database->remove($reactionWantDelete);
        $database->flush();

        //remove notification like
        $receiverId =  $postRepository->getUserIdFromAPost($idPostWantLike);
        $notificationRepository->removeNotificationLike($this->getUser()->getId(), $receiverId[0]['id']);

        return $reactionid;
    }

    /**
     * @Route ("/sendCommentPost", name="api_send_comment_post", methods="PUT")
     */
    public function getCommentPostAPI(Request $request, UserRepository $userRepository,PostRepository $postRepository, ManagerRegistry $managerRegistry)
    {
        $request = $this->tranform($request);
        $postId = $request->get('postId');
        $content = $request->get('content');

        $post = $postRepository->find($postId);
        if($post)
        {
            $database = $managerRegistry->getManager();

            $post->setTotalComment($post->getTotalComment() + 1);
            $database->persist($post);
            $database->flush();

            $this->saveComment($post, $content, $database);
            $this->saveCommentNotification($post, $postRepository, $userRepository, $database);

            $userId = $this->getUser()->getId();
            $FullnameUser = $userRepository->find($userId);

            return new JsonResponse([
                'status_code' => 200,
                'userId' => $userId,
                'avatar' => $FullnameUser->getAvatar(),
                'fullname' => $FullnameUser->getFullname(),
                'content' => $content,
                'dateTime' => new \DateTime(),
                'Message' => 'Successfully to send comment to post id: '.$postId
            ]);
        }
        else
        {
            return new JsonResponse(['status_code' => 400, 'Message' => 'Failed to send comment to post id: '.$postId]);
        }

    }
    public function saveComment($post, $content, $database)
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setUser($this->getUser());
        $comment->setCommentContent($content);
        $comment->setUploadTime(new \DateTime());


        $database->persist($comment);
        $database->flush();
    }

    public function saveCommentNotification($post, $postRepository, $userRepository, $database)
    {
        $commentNotification = new Notification();
        $receiverId = $postRepository->getUserIdFromAPost($post->getId());
        $receiver = $userRepository->find($receiverId[0]['id']);
        $commentNotification->setReceiver($receiver);
        $commentNotification->setSender($this->getUser());
        $commentNotification->setType('comment');
        $commentNotification->setSeen('no');

        $database->persist($commentNotification);
        $database->flush();
    }

    //tranform data when request by PUT method
    public function tranform($request)
    {
        $data = json_decode($request->getContent(), true);
        if($data === null){
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }
}
