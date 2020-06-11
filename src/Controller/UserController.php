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

        $picture = $request->files->get('picture');
        $data = json_decode($request->request->get('data'), true);

        $email = $data['username'];
        $password = $data['password'];
        $name = $data['name'];
        $surename = $data['surename'];
        $roles = $data['roles'];
        $lang = $data['lang'];
        $city_residence = $data['city_residence'];
        $group_age = $data['group_age'];
        $gender = $data['gender'];
        $age = $data['age'];
        $vat = $data['vat'];
        $address = $data['address'];

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
