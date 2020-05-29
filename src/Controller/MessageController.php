<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/message", name="message")
 */
class MessageController extends AbstractController
{
    /**
     * Create message
     * @param Request
     * @param RoomRepository
     * @param UserRepository
     * @param ValidatorInterface
     * @return jsonArray[]
     * @Route("/create", name="message_create", methods={"POST"})
     */
    public function messageCreate(Request $request, RoomRepository $roomRepository, UserRepository $userRepository, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        
        $room_id = $request->request->get('room_id');
        $receiver_ids = $request->request->get('receiver_id');
        $contents = $request->request->get('contents');
        
        $sender = $this->getUser();
        $room = $roomRepository->find($room_id);
        if(!$room)
        {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'The room is not existed.';
            return new JsonResponse($responseArray);
        }
        $status = false;

        $message = new Message($sender, $room, $contents, $status);
        foreach($receiver_ids as $receiver_id)
        {
            $receiver = $userRepository->find($receiver_id);
            if(!$receiver)
            {
                $responseArray['code'] = 404;
                $responseArray['message'] = "The receiver ID = $receiver_id not existed.";
                return new JsonResponse($responseArray);
            }
            $message->addReceiver($receiver);
        }

        $errors = $validator->validate($message);
        if(count($errors) > 0)
        {
            foreach($errors as $error)
            {
                $key = $error->getPropertyPath();
                $responseArray['code'] = $error->getCode();
                $responseArray[$key] = $error->getMessage();
                return new JsonResponse($responseArray);
            }
        }
        $em->persist($message);
        $em->flush();

        $responseArray['code'] = 200;
        $responseArray['message'] = 'Successfully';
        return new JsonResponse($responseArray);
    }
}
