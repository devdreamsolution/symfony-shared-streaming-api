<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AudioController extends AbstractController
{
    /**
     * Create audio
     * @param Request
     * @param RoomRepository
     * @param ValidatorInterface
     * @return jsonArray[]
     */
    public function audioCreate(Request $request, RoomRepository $roomRepository, ValidatorInterface $validator)
    {
        $responseArray = [];

        $em = $this->getDoctrine()->getManager();

        $room_id = $request->request->get('room_id');
        
        $room = $roomRepository->find($room_id);
        $audio_path = 'http://shared/audio.mp3';
        $recorder = $this->getUser();

        $audio = new Audio();
        $audio->setRoom($room);
        $audio->setRecorder($recorder);
        $audio->setAudioPath($audio_path);
        $audio->setCreatedAt();

        $errors = $validator->validate($audio);
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
        $em->persist($audio);
        $em->flush();

        $responseArray['code'] = 200;
        $responseArray['message'] = 'Succesfully';
        return new JsonResponse($responseArray);
    }
}
