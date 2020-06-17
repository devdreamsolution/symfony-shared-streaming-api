<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Repository\AudioRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
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
     * List audio
     * @param int $room_id
     * @param AudioRepository $audioRepository
     * @return jsonArray[]
     * @Route("/{room_id}/list", name="audio_list", methods={"GET"})
     */
    public function audioList(int $room_id, AudioRepository $audioRepository)
    {
        $responseArray = $audioRepository->transformByRoom($room_id);

        return new JsonResponse($responseArray);
    }

    /**
     * Get audio
     * @param int $audio_id
     * @param AudioRepository $audioRepository
     * @return jsonArray[]
     * @Route("/{audio_id}/view", name="audio_view", methods={"GET"})
     */
    public function audioView(int $audio_id, AudioRepository $audioRepository)
    {
        $responseArray = $audioRepository->transformOne($audio_id);

        return new JsonResponse($responseArray);
    }

    /**
     * Create audio
     * Only user who has ROLE_GUIDE as roles can access.
     * @param Request $request
     * @param RoomRepository $roomRepository
     * @param ValidatorInterface $validator
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
            return new JsonResponse([
                'success' => false,
                'message' => "Audio record with ID: $room_id not found.",
                'data' => null
            ]);
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
            return new JsonResponse([
                'success' => false,
                'message' => 'Form validate error',
                'data' => $responseArray
            ]);
        }

        $em->persist($audio);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => '',
            'data' => null
        ]);
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
    public function audioEdit(int $audio_id, Request $request, AudioRepository $audioRepository, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $audio = $audioRepository->find($audio_id);
        if (!$audio) {
            return new JsonResponse([
                'success' => false,
                'message' => "Audio record with ID: $audio_id not found.",
                'data' => null
            ]);
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
            return new JsonResponse([
                'success' => false,
                'message' => 'Form validate error',
                'data' => $responseArray
            ]);
        }

        $em->persist($audio);
        $em->flush();

        $data = $audioRepository->transform($audio);

        return new JsonResponse([
            'success' => true,
            'message' => '',
            'data' => $data
        ]);
    }

    /**
     * Delete audio
     * Only user who is recorder of this audio can delete.
     * @param int $audio_id
     * @param AudioRepository $audioRepository
     * @return jsonArray[]
     * @Route("/{audio_id}/delete", name="audio_delete", methods={"DELETE"})
     */
    public function audioDelete(int $audio_id, AudioRepository $audioRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $audio = $audioRepository->find($audio_id);
        if (!$audio) {
            return new JsonResponse([
                'success' => false,
                'message' => "Audio record with ID: $audio_id not found.",
                'data' => null
            ]);
        }

        $this->denyAccessUnlessGranted('DELETE', $audio);

        $em->remove($audio);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => '',
            'data' => null
        ]);
    }
}
