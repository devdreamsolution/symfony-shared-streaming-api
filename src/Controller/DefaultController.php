<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $email = $request->request->get('_email');
        $password = $request->request->get('_password');
        $name = $request->request->get('_name');
        $surename = $request->request->get('_surename');


        $user = new User();
        $user->setEmail($email);
        $user->setRoles('ROLE_USER');
        $user->setName($name);
        $user->setSurename($surename);
        $user->setCreatedAt();
        $user->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();

        return new Response(sprintf('User %s successfully created', $user->getRoles()));
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getEmail()));
    }
}
