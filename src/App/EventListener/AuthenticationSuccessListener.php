<?php

// src/App/EventListener/AuthenticationSuccessListener.php
namespace App\EventListener;

/**
 * @param AuthenticationSuccessEvent $event
 */

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        
        if (!$user instanceof UserInterface) {
            return;
        }

        $data['userdata'] = array(
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        );

        $event->setData($data);
    }
}