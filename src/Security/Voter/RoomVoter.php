<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Room;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RoomVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $room)
    {
        return in_array($attribute, ['CREATE', 'VIEW', 'EDIT', 'DELETE'])
            && $room instanceof \App\Entity\Room;
    }

    protected function voteOnAttribute($attribute, $room, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'CREATE':
                return $this->canCreate();
                break;
            case 'EDIT':
                return $this->canEditDel($room, $user);
                break;
            case 'DELETE':
                return $this->canEditDel($room, $user);
                break;
        }

        return false;
    }

    /**
     * Room create permission
     * Only ROLE_GUIDE can access
     * @return bool
     */
    private function canCreate()
    {
        return $this->security->isGranted('ROLE_GUIDE');
    }

    /**
     * Room edit and delete permission
     * Only owner can access
     * @param Room $room
     * @param User $user
     * @return bool
     */
    private function canEditDel(Room $room, User $user)
    {
        return $room->getOwner() == $user;
    }
}
