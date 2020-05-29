<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Repository\AudioRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/audio", name="audio")
 */
class AudioController extends AbstractController
{
    /**
     * Create audio
     * Only user who has ROLE_GUIDE as roles can access.
     * @param Request
     * @param RoomRepository
     * @param ValidatorInterface
     * @return jsonArray[]
     * @Route("/create", name="audio_create", methods={"POST"})
     */
    public function audioCreate(Request $request, RoomRepository $roomRepository, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        if(!$this->isGranted('ROLE_GUIDE'))
        {
            $responseArray['code'] = 403;
            $responseArray['message'] = 'Only ROLE_GUIDE can access this function';

            return new JsonResponse($responseArray);
        }
        
        $room_id = $request->request->get('room_id');

        $room = $roomRepository->find($room_id);
        if(!$room)
        {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'The room is not existed.';
            return new JsonResponse($responseArray);
        }
        $recorder = $this->getUser();
        $audio_path = 'http://shared/audio.mp3';    // after uploading the audio file, put the path.
        
        $audio = new Audio($room, $recorder, $audio_path);

        $errors = $validator->validate($audio);
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

        $em->persist($audio);
        $em->flush();

        return new JsonResponse('Succesfully');
    }

    /**
     * Delete audio
     * Only user who is recorder of this audio can delete.
     * @param int $audio_id
     * @param AudioRepository
     * @return jsonArray[]
     * @Route("/{audio_id}/delete", name="audio_delete", methods={"POST"})
     */
    public function audioDelete(int $audio_id, AudioRepository $audioRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $audio = $audioRepository->find($audio_id);
        if(!$audio)
        {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'The audio is already not existed';
            return new JsonResponse($responseArray);
        }

        if($this->getUser() != $audio->getRecorder())   // Only the recoder can delete the audio.
        {
            $responseArray['code'] = 401;
            $responseArray['message'] = 'You can not delete this audio because of not recorder.';
            return new JsonResponse($responseArray);
        }

        $em->remove($audio);
        $em->flush();

        return new JsonResponse('Successfully');
    }
}
