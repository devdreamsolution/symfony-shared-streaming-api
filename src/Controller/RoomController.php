<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends AbstractController
{
    /**
     * All room list
     * @param RoomRepository
     * @return jsonArray[]
     */
    public function roomList(RoomRepository $roomRepository)
    {
        $roomList = $roomRepository->transformAll();
        
        return new JsonResponse($roomList);
    }

    /**
     * Create room
     * @param Request
     * @param ValidatorInterface
     * @return jsonArray[]
     */
    public function roomCreate(Request $request, ValidatorInterface $validator)
    {
        $responseArray = [];
        $em = $this->getDoctrine()->getManager();

        if(!$this->isGranted('ROLE_GUIDE')) {
            $responseArray['code'] = 403;
            $responseArray['message'] = 'Only ROLE_GUIDE can access this function';

            return new JsonResponse($responseArray);
        }
        $owner = $this->getUser();

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $qr_url = 'http://testurl.com';
        $start_time = new \DateTime();

        $room = new Room();
        $room->setOwner($owner);
        $room->addUser($owner);                                             // yet not multi, that is needed multi like message add receivers
        $room->setName($name);
        $room->setDescription($description);
        $room->setQrUrl($qr_url);
        $room->setStartTime($start_time);
        $room->setCreatedAt();

        $errors = $validator->validate($room);
        if(count($errors) > 0)
        {
            foreach($errors as $error)
            {
                $key = $error->getPropertyPath();
                $responseArray['code'] = $error->getCode();
                $responseArray[$key] = $error->getMessage();
            }
            return new JsonResponse($responseArray);
        }

        $em->persist($room);
        $em->flush();

        $responseArray['code'] = 200;
        $responseArray['message'] = 'Succesfully';
        return new JsonResponse($responseArray);
    }

    /**
     * Delete room
     * @param roomID
     * @param RoomRepository
     * @return jsonArray[]
     */
    public function roomDelete($id, RoomRepository $roomRepository)
    {
        $responseArray = [];
        $em = $this->getDoctrine()->getManager();

        if(!$id)
        {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'Room ID is null.';
            return new JsonResponse($responseArray);
        }
        $room = $roomRepository->find($id);

        if(!$room)
        {
            $responseArray['code'] = 404;
            $responseArray['message'] = 'The room already is not existed.';
            return new JsonResponse($responseArray);
        }
        
        $em->remove($room);
        $em->flush();
        
        $responseArray['code'] = 200;
        $responseArray['message'] = 'Successfully';
        return new JsonResponse($responseArray);
    }
}
