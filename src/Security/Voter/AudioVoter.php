<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Audio;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AudioVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $audio)
    {
        return in_array($attribute, ['CREATE', 'VIEW', 'EDIT', 'DELETE'])
            && $audio instanceof \App\Entity\Audio;
    }

    protected function voteOnAttribute($attribute, $audio, TokenInterface $token)
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
                return $this->canCreate($audio, $user);
                break;
            case 'EDIT':
                return $this->canEditDel($audio, $user);
                break;
            case 'VIEW':
                return $this->canView($audio, $user);
                break;
            case 'DELETE':
                return $this->canEditDel($audio, $user);
                break;
        }

        return false;
    }
    /**
     * Audio create permission
     * Only room owner && ROLE_GUIDE of audio can access
     * @param Audio $audio
     * @param User $user
     * @resutn bool
     */
    private function canCreate(Audio $audio, User $user)
    {
        return $audio->getRoom()->getOwner() == $user && $this->security->isGranted('ROLE_GUIDE');
    }

    /**
     * Audio edit and delete permission
     * Only recoder of audio or room owner can access
     * @param Audio $audio
     * @param User $user
     * @return bool
     */
    private function canEditDel(Audio $audio, User $user)
    {
        return $audio->getRecorder() == $user || $audio->getRoom()->getOwner() == $user;
    }

    /**
     * Audio view permission
     * Only users who be in room of audio can access
     * @param Audio $audio
     * @param User $user
     * @return bool
     */
    private function canView(Audio $audio, User $user)
    {
        return $audio->getRoom()->getUser() == $user;
    }

}
