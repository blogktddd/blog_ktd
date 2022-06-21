<?php
    namespace App\Controller;

    use App\Repository\NotificationRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class NotificationController extends AbstractController
    {
        /**
         * @Route ("/seenNotification", name="api_seen_notification", methods={"PUT"})
         */
        public function seenNotificationAPI(NotificationRepository $notificationRepository)
        {
            $seen = $notificationRepository->updateSeenStatusNotification($this->getUser()->getId());
            if($seen)
            {
                return new JsonResponse(['status_code' => 200, 'Message' => 'Seen notification like and comment']);
            }
            else
            {
                return new JsonResponse(['status_code' => 400, 'Message' => 'something wrong']);
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