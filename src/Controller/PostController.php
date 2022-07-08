<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
{
    /**
     * @Route ("/Post/new", name="api_new_post", methods="POST")
     */
    public function newPostAction(PostRepository $postRepository, ManagerRegistry $managerRegistry, UserRepository $userRepository)
    {
        $imgFile = $_FILES['imgPost'];
        $caption = $_POST['captionPost'];

        $error = $this->checkPost($imgFile, $caption);

        if($error != '')
        {
            return new JsonResponse(['status_code' => 400,'Message' => $error]);
        }
        else
        {
            $post = new Post();
            $user = $this->getUser();

            if($imgFile['name'] != '')
            {
                $safeFileImg = uniqid().$imgFile['name'];
                copy($imgFile['tmp_name'], "image/post/".$safeFileImg);
                $post->setImage($safeFileImg);
            }

            $post->setUser($user);
            $post->setCaption($caption);
            $post->setUploadTime(new \DateTime());
            $post->setDeleted('false');

            //save data
            $database = $managerRegistry->getManager();
            $database->persist($post);
            $database->flush();

            return new JsonResponse([
                'status_code' => 200,
                'Message' => 'success',
                'userId' => $this->getUser()->getId()
            ]);
        }

    }

    /**
     * @Route ("/post/updatePost", name="app_update_post", methods={"POST"})
     */
    public function updatePostAction(Request $request, PostRepository $postRepository, ManagerRegistry $managerRegistry)
    {
        $postId = $_POST['postId'];
        $image = $_FILES['imgPost'];
        $caption = $_POST['captionPost'];

        $error = $this->checkPost($image,$caption);

        if($error != '')
        {
            return new JsonResponse(['status_code' => 400,'Message' => $error]);
        }
        else
        {
            $post = $postRepository->find($postId);

            if($image['name'] != '')
            {
                $safeFileImg = uniqid().$image['name'];
                copy($image['tmp_name'], "image/post/".$safeFileImg);
                $post->setImage($safeFileImg);
            }

            $post->setCaption($caption);

            //save data
            $database = $managerRegistry->getManager();
            $database->persist($post);
            $database->flush();

            return new JsonResponse([
                'status_code' => 200,
                'Message' => 'Update post successfully with id: '.$post->getId(),
                'userId' => $this->getUser()->getId()
            ]);
        }
    }

    /**
     * @Route ("/profile/deletePost", name="api_delete_post", methods="DELETE")
     */
    public function deletePostAction(Request $request, UserRepository $userRepository, PostRepository $postRepository, ManagerRegistry $managerRegistry)
    {
        //get data from request
        $request = $this->tranform($request);
        $idPost = $request->get('idPost');

        //get avatar user
        $userId = $postRepository->getUserIdFromAPost($idPost);
        $user = $userRepository->find($userId[0]['id']);

        $post = $postRepository->find($idPost);

        if($post)
        {
            //avoid delete avatar
            if($post->getImage() != null && $post->getImage() != $user->getAvatar())
            {
                unlink('image/post/'.$post->getImage());
            }

            $post->setDeleted('true');

            $database = $managerRegistry->getManager();
            $database->persist($post);
            $database->flush();

            return new JsonResponse(['status_code' => 200, 'postId' => $idPost]);
        }
        else
        {
            return new JsonResponse([
                'status_code' => 400,
                'Message' => 'Cannot found the post with id: '.$idPost
            ]);
        }

    }

    /**
     * @Route ("/post/getInforPost", name="api_edit_post", methods="GET")
     */
    public function getInforPostAction(PostRepository $postRepository)
    {
        $idPost = $_GET['idPost'];

        $post = new Post();
        $post = $postRepository->find($idPost);
        if($post)
        {
            return new JsonResponse([
                'status_code' => 200,
                'caption' => $post->getCaption(),
                'image' => $post->getImage()
            ]);
        }
        else
        {
            return new JsonResponse([
                'status_code' => 400,
                'Message' => 'Cannot found the post with id: '.$idPost
            ]);
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

    public function checkPost($imgFile, $caption)
    {
        $error = '';
        if($imgFile['name'] != '')
        {
            if(!($imgFile["type"] =="image/jpg" || $imgFile['type'] == "image/jpeg" || $imgFile['type'] == "image/png"))
            {
                $error = 'Only accept .jpg, .jpeg and .png file.';
            }
        }
        else if($imgFile['name'] != '' && $caption == '')
        {
            $error = 'Content must not be empty.';
        }

        return $error;
    }
}
