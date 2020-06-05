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
 * @Route("/api/audio", name="audio")
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

        $room_id = $request->request->get('room_id');
        $file = $request->files->get('audio');

        $room = $roomRepository->find($room_id);
        if (!$room) {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'The room is not existed.';
            return new JsonResponse($responseArray);
        }
        $recorder = $this->getUser();
        
        $audio = new Audio($room, $recorder, $file);

        $this->denyAccessUnlessGranted('CREATE', $audio);
        $errors = $validator->validate($audio);
        if(count($errors) > 0) {
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
     * Edit audio
     * Only user who is recorder of this audio can edit
     * @param Audio $audio
     * @param int $audio_id
     * @param Request $request
     * @param ValidatorInderface $validator
     * @Route("/{audio_id}/edit", name="audio_edit", methods={"POST"})
     */
    public function audioEdit($audio_id, Request $request, AudioRepository $audioRepository, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $audio = $audioRepository->find($audio_id);
        if (!$audio) {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'The audio is not existed';
            return new JsonResponse($responseArray);
        }
        
        $this->denyAccessUnlessGranted('EDIT', $audio);
        
        $file = $request->files->get('audio');

        $audio->setAudio($file);

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

        return new JsonResponse('Successfully');
    }

    /**
     * Delete audio
     * Only user who is recorder of this audio can delete.
     * @param int $audio_id
     * @param AudioRepository
     * @return jsonArray[]
     * @Route("/{audio_id}/delete", name="audio_delete", methods={"DELETE"})
     */
    public function audioDelete(int $audio_id, AudioRepository $audioRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $audio = $audioRepository->find($audio_id);
        if (!$audio) {
            $responseArray['code'] = 400;
            $responseArray['message'] = 'The audio is already not existed';
            return new JsonResponse($responseArray);
        }

        $this->denyAccessUnlessGranted('DELETE', $audio);

        $em->remove($audio);
        $em->flush();

        return new JsonResponse('Successfully');
    }
}
