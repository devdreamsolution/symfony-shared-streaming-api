<?php

namespace App\EventListener;

/**
 * @param AuthenticationSuccessEvent $event
 */

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private $baseURL;

    public function __construct()
    {
        $this->baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        
        if (!$user instanceof UserInterface) {
            return;
        }

        $data['userdata'] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'name' => $user->getName(),
            'surename' => $user->getSurename(),
            'picture' => $this->baseURL . '/' . $user->getPicture()
        );

        $event->setData($data);
    }
}