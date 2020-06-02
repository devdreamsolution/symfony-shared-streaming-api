<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * Register user
     * @param Request
     * @param UserPasswordEncoderInterface
     * @param ValidatorInterface
     * @return jsonArray[]
     * @Route("/register", name="user_register", methods={"POST"})
     */
    public function userRegister(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
	{
		$em = $this->getDoctrine()->getManager();

		$email = $request->request->get('username');
		$password = $request->request->get('password');
		$name = $request->request->get('name');
		$surename = $request->request->get('surename');
        $roles = $request->request->get('roles');
        $lang = $request->request->get('lang');
        $city_residence = $request->request->get('city_residence');
        $group_age = $request->request->get('group_age');
        $gender = $request->request->get('gender');
        $age = $request->request->get('age');
        $vat = $request->request->get('vat');
        $address = $request->request->get('address');
        $picture = $request->files->get('picture');  // file upload

		if ($roles == 0) {          // ROLE_TOURIST
            $roles = ['ROLE_TOURIST'];
		}
		else if ($roles == 1) {    // ROLE_GUIDE
            $roles = ['ROLE_GUIDE'];
        }
        else {                     // ROLE_ADMIN (ROLE_GUIDE, ROLE_TOURIST)
            $roles = ['ROLE_GUIDE', 'ROLE_TOURIST'];
        }

        $user = new User($email, $name, $surename, $roles, $lang, $city_residence, $address, $group_age, $gender, $age, $vat, $picture);
        $user->setPassword($encoder->encodePassword($user, $password));

		$errors = $validator->validate($user);
		if (count($errors) > 0) {
			foreach($errors as $error) {
				$key = $error->getPropertyPath();
				$responseArray['code'] = $error->getCode();
                $responseArray[$key] = $error->getMessage();
            }
            return new JsonResponse($responseArray);
		}

		$em->persist($user);
		$em->flush();

		return new JsonResponse('Successfully');
	}
}
