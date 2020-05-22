<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
	public function index()
	{
		return $this->render('user/index.html.twig', [
			'controller_name' => 'UserController',
		]);
	}

	public function register(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
	{
		$em = $this->getDoctrine()->getManager();

		$email = $request->request->get('username');
		$password = $request->request->get('password');
		$name = $request->request->get('name');
		$surename = $request->request->get('surename');
		$roles = $request->request->get('roles');

		$user = new User();
		if ($roles == 0)							// tuorist
		{
			$city_residence = $request->request->get('city_residence');
			$group_age = $request->request->get('group_age');
			$gender = $request->request->get('gender');

			$user->setCityResidence($city_residence);
			$user->setGroupAge($group_age);
			$user->setGender($gender);
			$user->setRoles('ROLE_TUORIST');
		}
		else if ($roles == 1)						// guide
		{
			$age = $request->request->get('age');
			$vat = $request->request->get('vat');
			$address = $request->request->get('address');

			$user->setRoles('ROLE_GUIDE');
			$user->setPicture('');                  // file upload
			$user->setAge($age);
			$user->setVat($vat);
			$user->setAddress($address);
		}
		$user->setEmail($email);
		$user->setName($name);
		$user->setSurename($surename);
		$user->setPassword($encoder->encodePassword($user, $password));
		$user->setCreatedAt();

		$errors = $validator->validate($user);
		if (count($errors) > 0) {
			$errorArray = [];
			foreach($errors as $error)
			{
				$key = $error->getPropertyPath();
				$errorArray[$key] = $error->getMessage();
			}
			return new JsonResponse($errorArray);
		}

		$em->persist($user);
		$em->flush();

		return new Response(sprintf('User %s successfully created', $user->getEmail()));
	}
}
